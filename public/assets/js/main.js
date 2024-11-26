$(function () {
    handleView()

    window.onpopstate = () => {
        handleView()
    }
})

const pushState = async (url) => {
    await handleView(url)
    window.history.pushState(null, null, url)
}

const handleView = async (url = null) => {
    Pace.restart()
    $('v-loader-page').show()

    if($('v-rendering').length <= 0) {
        $('v-content').removeClass('show')
        Pace.restart()
        await checkAuth()
    }

    Pace.restart()
    const res = await fetch(url ?? window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })

    if (res.status == 200) {
        const data = await res.text()
        $('v-loader-page').hide()

        if ($('v-rendering').length > 0) {
            $('v-rendering').html(data)
            if (!$('v-content').hasClass('show')) {
                $('v-content').addClass('show')
            }
        } else {
            $('v-content').html(data)
            $('v-content').addClass('show')
            handleEvent()
        }

        if ($(".js-choices").length) {
            $.each($(".js-choices"), (key, val) => {
                new Choices(val, {
                    duplicateItemsAllowed: false,
                    position: "bottom",
                    placeholder: true,
                    placeholderValue: "Choose "
                });
            });
        }

        $('.dropify').dropify()

        App()
        handleEvent()
        // sidebarToggler()
    } else {
        handleError(res.status)
    }
}

const handleEvent = () => {
    $('a[data-toggle="ajax"]').unbind().on('click', function (e) {
        e.preventDefault()
        pushState($(this).attr('href'))
    })

    $('button[data-toggle="ajax"]').unbind().on('click', function (e) {
        e.preventDefault()
        pushState($(this).data('target'))
    })

    $('button[data-toggle="edit"]').unbind().on('click', function (e) {
        e.preventDefault()
        pushState(`${window.location.href}/${$(this).data('id')}/edit`)
    })

    $('button[data-toggle="delete"]').unbind().on('click', function (e) {
        e.preventDefault()
        deleteAction(this)
    })

    $('a[data-toggle="logout"]').unbind().on('click', function (e) {
        e.preventDefault()
        swal.fire({
            title: $(this).data('title'),
            icon: 'question',
            text: $(this).data('text') ?? 'Are you sure?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then(async (result) => {
            if (result.isConfirmed) {
                swal.fire({
                    title: 'Please wait...',
                    text: 'Logging out...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        swal.showLoading()
                    }
                })

                const res = await fetch($(this).attr('href'), {
                    method: $(this).data('method') ?? 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $(this).data('token'),
                        'Content-Type': 'application/json'
                    }
                })

                swal.close()
                if (res.status == 200) {
                    const data = await res.json()
                    swal.fire({
                        title: 'Success',
                        icon: 'success',
                        text: data.message
                    }).then(result => {
                        if (typeof $(this).data('callback') != 'undefined') {
                            $('v-content').html('')
                            pushState($(this).data('callback'))
                        } else {
                            if (typeof table != "undefined") table.ajax.reload()
                            else handleView()
                        }
                    })
                }
            }
        })
    })

    $('form[data-request="ajax"]').unbind().on('submit', async function (e) {
        e.preventDefault()
        var btn = $(this).find('button[type="submit"]').html()
        $(this).find('button[type="submit"]').html('<i class="fa fa-spinner fa-spin"></i> Loading...').attr('disabled', true)
        resetInvalid()

        const res = await fetch($(this).attr('action'), {
            method: $(this).attr('method'),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: generateFormBody(new FormData(this))
        })

        $(this).find('button[type="submit"]').html(btn).removeAttr('disabled')
        if (res.status == 200) {
            const data = await res.json()
            toastity(data.message, 'success')

            $('.modal').modal('hide')
            $('.modal-backdrop').remove()
            $('.modal-open').removeClass('modal-open')

            if ($(this).data('redirect')) {
                if (typeof $(this).data('callback') != 'undefined') {
                    window.location.assign($(this).data('callback'))
                } else {
                    window.location.reload()
                }
            } else {
                if (typeof table != 'undefined') {
                    if (typeof $(this).data('callback') != 'undefined') {
                        pushState($(this).data('callback'))
                    } else {
                        table.ajax.reload()
                    }
                } else {
                    if (typeof $(this).data('callback') != 'undefined') {
                        pushState($(this).data('callback'))
                    } else {
                        handleView()
                    }
                }
            }
        } else {
            if (res.status == 422) {
                const data = await res.json()
                showInvalid(data.errors)
            } else {
                if (res.status == 401) {
                    window.location.reload()
                } else {
                    toastity('Opps! Something went wrong!', 'danger')
                }
            }
        }
    })
}

const generateFormBody = (form) => {
    var formData = new FormData()
    form.forEach(function (value, key) {
        formData.append(key, value)
    })

    return formData
}

const showInvalid = (errorMessages) => {
    console.log(errorMessages)
    for (errorField in errorMessages) {
        if ($(`.form-control[name="${errorField}"]`).parent().hasClass('choices__inner')) {
            $(`.form-control[name="${errorField}"]`).parent().parent().parent().append(`<div class="small text-danger py-1 choices-invalid">${errorMessages[errorField]}</div>`);
            $(`.form-control[name="${errorField}"]`).parent().addClass("border-danger");
        } else {
            $(
                `<div class="invalid-feedback">${errorMessages[errorField]}</div>`
            ).insertAfter(`.form-control[name="${errorField}"]`);
            $(`.form-control[name="${errorField}"]`).addClass("is-invalid");
        }
    }
};

const resetInvalid = () => {
    for (const el of $(".form-control")) {
        $(el).removeClass("is-invalid");
        $('.choices__inner').removeClass("border-danger");
        $(el).siblings(".invalid-feedback").remove();
        $(".invalid-feedback").remove();
        $(".choices-invalid").remove();
    }
};

const initTable = (el, columns = [], drawCallback = null) => {
    if (!$.fn.DataTable.isDataTable(el)) {

    }

    var opt = {
        processing: true,
        serverSide: true,
        ajax: $(el).data('url'),
        columns: columns,
        responsive: true,
        search: {
            return: true,
        },
         lengthMenu: [
             [10, 25, 50, 100, -1],
             [10+" baris", 25+" baris", 50+" baris", 100+" baris", "Semua baris"]
         ],
        language: {
            sLengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Pencarian..."
        },
        dom: '<"col-sm-4 col-12"f><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"l><"col-sm-12 col-md-7"p>>',
        drawCallback,
    }
    var table = $(el).DataTable(opt)

    table.on('draw.dt', function () {
        handleEvent()
        dtCheckbox()
    })

    table.on('responsive-display', function () {
        handleEvent()
        dtCheckbox()
    })

    return table
}

const checkAuth = async () => {
    sessionStorage.setItem('sidebar', '')
    const res = await fetch(`${$('meta[name="base-url"]').attr('content')}/auth/check`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })

    var data = await res.text()
    $('v-content').html(data)
}

const handleError = async (status) => {
    const res = await fetch(`${$('meta[name="base-url"]').attr('content')}/errors?status=${status}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })

    $('v-loader-page').hide()
    if (res.status == 200) {
        const data = await res.text()
        $('v-content').html(data)
        handleEvent()
    } else {
        toastify('Opps! Something went wrong!', 'danger')
    }
}

const toastity = (message, type = 'success') => {
    $('.toast-message').html(message)
    var el = type == 'success' ? $('#toastSuccess') : (type == 'primary' ? $('#toastPrimary') : (type == 'danger' ? $('#toastDanger') : $('#toastWarning')))
    el.toast('show')
}

const dtCheckbox = () => {
    $('#dt-checkbox').unbind().on('change', function () {
        $('.dt-checkbox').prop('checked', this.checked)

        btnCheckbox()
    })

    $('.dt-checkbox').unbind().on('change', function () {
        if ($('.dt-checkbox:checked').length == $('.dt-checkbox').length) {
            $('#dt-checkbox').prop('checked', true)
        } else {
            $('#dt-checkbox').prop('checked', false)
        }

        btnCheckbox()
    })

    $('#dt-unselect').unbind().on('click', function () {
        $('.dt-checkbox').prop('checked', false)
        $('#dt-checkbox').prop('checked', false)

        btnCheckbox()
    })
}

const btnCheckbox = () => {
    if ($('.dt-checkbox:checked').length > 0) {
        $('#dt-unselect').removeClass('d-none')
        $('#bulkDelete').removeClass('d-none')
    } else {
        $('#dt-unselect').addClass('d-none')
        $('#bulkDelete').addClass('d-none')
    }
}

const deleteAction = (el) => {
    Swal.fire({
        html: '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon><div class="mt-4 pt-2 fs-15 mx-5"><h4>Are you Sure ?</h4><p class="text-muted mx-4 mb-0">Are you Sure You want to Delete this Account ?</p></div></div>',
        showCancelButton: !0,
        confirmButtonClass: "btn btn-primary w-xs me-2 mb-1",
        confirmButtonText: "Yes, Delete It!",
        cancelButtonClass: "btn btn-danger w-xs mb-1",
        buttonsStyling: !1,
        showCloseButton: !0
    }).then(async result => {
        if (result.isConfirmed) {
            $('v-loader-page').show()
            const res = await fetch(`${window.location.href}/${$(el).data('id')}/delete`, {
                method: 'delete',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $('v-loader-page').hide()
            if (res.status == 200) {
                const data = await res.json()
                toastity(data.message, 'success')
                if (typeof table != 'undefined') table.ajax.reload()
                else handleView()
            } else {
                if (res.status == 401) {
                    window.location.reload()
                } else {
                    const data = await res.json()
                    toastity(data.message, 'warning')
                }
            }
        }
    })
}

const sidebarToggler = () => {
    $('.vertical-menu-btn').unbind().on('click', function (e) {
        e.preventDefault()

    })
}

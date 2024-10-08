$(function(){
    toastr.option = {
        showMethod: "slideLeft",
        hideMethod: "slidRight"
    }

    $('[data-toggle="tooltip"]').tooltip()
    $('select').select2()
    $('#input_nama_outlet').val(OUTLET[0].NAMA)
    $('#input_id_outlet').val(OUTLET[0].ID_OUTLET)

    clear_timetable(OUTLET[0].ID_OUTLET)
    clear_tanggal_penting(OUTLET[0].ID_OUTLET)

    if(CATEGORY_ACTIVITY.length > 0){
        draw_category(CATEGORY_ACTIVITY[0].ID_OUTLET)
        draw_tanggal_penting(CATEGORY_ACTIVITY[0].ID_OUTLET)
    }
    
    let durasi = -1
    let tahun_aktif = $('#tahun-aktif').val()
})

// Switch antar outlet, mentrigger perubahan data pada timetable
function switch_outlet(select_outlet){
    let id_outlet = $(select_outlet).val()
    $('#input_category').empty()

    for(let i = 0; i < OUTLET.length; i++){
        if(OUTLET[i].ID_OUTLET == id_outlet){
            $('#title-outlet-name').html(OUTLET[i].NAMA)
            $('#input_nama_outlet').val(OUTLET[i].NAMA)
            $('#input_id_outlet').val(OUTLET[i].ID_OUTLET)
            break
        }
    }

    for(let i = 0; i < CATEGORY_ACTIVITY.length; i++){
        if(CATEGORY_ACTIVITY[i].ID_OUTLET == id_outlet){
            $('#input_category').append(new Option(CATEGORY_ACTIVITY[i].NAMA, CATEGORY_ACTIVITY[i].ID_CATEGORY))
        }
    }

    clear_timetable(id_outlet)
    clear_tanggal_penting(id_outlet)
    draw_category(id_outlet)
    draw_tanggal_penting(id_outlet)
    get_timeline(id_outlet, $('#tahun-aktif').val())
    draw_timeline(timeline)
}

/**
 * Function untuk mentrigger perubahan data pada saat bepindah tahun
 * @param {*} switch_btn 
 */
function switch_year(switch_btn){
    let navigation = $(switch_btn).data('navigation')
    let year = parseInt($('#tahun-aktif').val())
    let id_outlet = $('#select-outlet').val()

    if(navigation == 'prev'){
        year = year - 1
    } else if(navigation == 'next'){
        year = year + 1
    }

    $('#tahun-aktif').val(year)
    $('.display-tahun-aktif').html(year)

    clear_timetable(id_outlet)
    clear_tanggal_penting(id_outlet)
    draw_category(id_outlet)
    draw_tanggal_penting(id_outlet)
    get_timeline(id_outlet, year)
    draw_timeline(timeline)
}

// Trigger function untuk menambahkan category ke dalam timetable
function draw_category(id_outlet){
    for(let i = 0; i < CATEGORY_ACTIVITY.length; i++){
        if(CATEGORY_ACTIVITY[i].ID_OUTLET == id_outlet){
            put_category_into_table(CATEGORY_ACTIVITY[i].NAMA)
            draw_activity(CATEGORY_ACTIVITY[i].ID_CATEGORY)
        }
    }
}

// Menambahkan tiap-tiap kategori ke timetable
function put_category_into_table(category_name){
    let tr =    `<tr style="background-color: #F4F4F7;">
                    <td style="text-align:left;">${category_name}</td>
                    <td></td>
                    <td colspan="48">${category_name}</td>
                </tr>
                `
    $('#timetable-tbody').append(tr)
}

// Trigger function menambahkan activity ke timetable
function draw_activity(id_category){
    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_CATEGORY == id_category && DETAIL_ACTIVITY[i].FLAG == 0){
            put_activity_into_table(DETAIL_ACTIVITY[i])
        }
    }
}

// Menambahkan activity ke timetable
function put_activity_into_table(activity){
    let tr =    `<tr id="tr_${activity.ID_DETAIL_ACTIVITY}" style="background-color: #FFFFFF;">
                    <td style="text-align:left; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="click to view detail"
                        data-id="${activity.ID_DETAIL_ACTIVITY}" onclick="view_progress_activity(this)">
                        ${activity.NAMA_AKTIFITAS}
                    </td>
                    <td>${activity.DURASI.days + 1} Hari</td>
                    <td class="week-1"></td>
                    <td class="week-2"></td>
                    <td class="week-3"></td>
                    <td class="week-4"></td>
                    <td class="week-5"></td>
                    <td class="week-6"></td>
                    <td class="week-7"></td>
                    <td class="week-8"></td>
                    <td class="week-9"></td>
                    <td class="week-10"></td>
                    <td class="week-11"></td>
                    <td class="week-12"></td>
                    <td class="week-13"></td>
                    <td class="week-14"></td>
                    <td class="week-15"></td>
                    <td class="week-16"></td>
                    <td class="week-17"></td>
                    <td class="week-18"></td>
                    <td class="week-19"></td>
                    <td class="week-20"></td>
                    <td class="week-21"></td>
                    <td class="week-22"></td>
                    <td class="week-23"></td>
                    <td class="week-24"></td>
                    <td class="week-25"></td>
                    <td class="week-26"></td>
                    <td class="week-27"></td>
                    <td class="week-28"></td>
                    <td class="week-29"></td>
                    <td class="week-30"></td>
                    <td class="week-31"></td>
                    <td class="week-32"></td>
                    <td class="week-33"></td>
                    <td class="week-34"></td>
                    <td class="week-35"></td>
                    <td class="week-36"></td>
                    <td class="week-37"></td>
                    <td class="week-38"></td>
                    <td class="week-39"></td>
                    <td class="week-40"></td>
                    <td class="week-41"></td>
                    <td class="week-42"></td>
                    <td class="week-43"></td>
                    <td class="week-44"></td>
                    <td class="week-45"></td>
                    <td class="week-46"></td>
                    <td class="week-47"></td>
                    <td class="week-48"></td>
                </tr>
                `
    $('#timetable-tbody').append(tr)
}

// Trigger function menambahkan activity ke table tanggal-tanggal penting
function draw_tanggal_penting(id_outlet){
    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_OUTLET == id_outlet && DETAIL_ACTIVITY[i].FLAG == 1){
            put_tanggal_penting_into_table(DETAIL_ACTIVITY[i])
        }
    }
}

// Menambahkan activity ke table tanggal-tanggal penting
function put_tanggal_penting_into_table(activity){
    let tr =    `<tr style="background-color: #FFFFFF;">
                    <td style="text-align:left; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="click to view detail"
                        data-id="${activity.ID_DETAIL_ACTIVITY}" onclick="view_progress_activity(this)">
                        ${activity.NAMA_AKTIFITAS}
                    </td>
                    <td>${activity.TANGGAL_START}</td>
                    <td>${activity.TANGGAL_END}</td>
                </tr>`

    $('#tanggal-penting-tbody').append(tr)
}

/**
 * Menghitung durasi antara start date dan end date
 * pada form input activity
 */
 function calculate_activity_duration(){
    let start_date = $('#input_tanggal_mulai').val()
    let end_date = $('#input_tanggal_selesai').val()
    let durasi

    if(start_date != '' && end_date != ''){
        start_date = new Date(start_date)
        end_date = new Date(end_date)

        let diff_in_time = end_date.getTime() - start_date.getTime()
        let diff_in_days = diff_in_time / (1000 * 3600 * 24)
        durasi = diff_in_days

        if(diff_in_days < 0){
            $('#input_durasi').val('')
            $('#input_durasi_error_msg').css('display', 'block')
        } else {
            $('#input_durasi').val(`${diff_in_days + 1}`)
            $('#input_durasi_error_msg').css('display', 'none')
        }
    }
}

function update_date_from_duration(){
    let start_date = $('#input_tanggal_mulai').val()
    let end_date = $('#input_tanggal_selesai').val()
    let durasi = $('#input_durasi').val()

    if(start_date != '' || start_date != null){
        let tmp_end_date = new Date()
            start_date = new Date(start_date)
            tmp_end_date.setDate(start_date.getDate() + (parseInt(durasi) - 1))
            let res_year = String(tmp_end_date.getFullYear())
            let res_month = parseInt(tmp_end_date.getMonth() + 1)
            let res_date = parseInt(tmp_end_date.getDate())
            
            if(res_month < 10){
                res_month = `0${String(res_month)}`
            } else {
                res_month = String(res_month)
            }
            
            if(res_date < 10){
                res_date = `0${String(res_date)}`
            } else {
                res_date = String(res_date)
            }

            let res = `${res_year}-${res_month}-${res_date}`

            $('#input_tanggal_selesai').val(res)
    }
}

/**
 * Melakukan check pada form input activity
 * memastikan semua input form yang dibutuhkan terisi
 */
function add_activity_form_check(){
    let form_status = 1
    let category = $('#input_category').val()
    let nama_activity = $('#input_nama_activity').val()
    let tanggal_mulai = $('#input_tanggal_mulai').val()
    let tanggal_selesai = $('#input_tanggal_selesai').val()
    let pic = $('#input_pic').val()

    if(category == '' || category == null){
        form_status = 0
        $('#input_category_error_msg').css('display', 'block')
    }
    
    if(nama_activity == '' || nama_activity == null){
        form_status = 0
        $('#input_nama_activity_error_msg').css('display', 'block')
    }
    
    if(tanggal_mulai == '' || tanggal_mulai == null){
        form_status = 0
        $('#input_tanggal_mulai_error_msg').css('display', 'block')
    }
    
    if(tanggal_selesai == '' || tanggal_selesai == null){
        form_status = 0
        $('#input_tanggal_selesai_error_msg').css('display', 'block')
    }
    
    if(durasi < 0){
        form_status = 0
        $('#input_durasi_error_msg').css('display', 'block')
    }

    if(pic == '' || pic == null){
        form_status = 0
        $('#input_pic_error_msg').css('display', 'block')
    }

    if(form_status == 1){
        return true
    } else {
        return false
    }
}

// Modal progress activity
function view_progress_activity(activity_el){
    var detail 

    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY == $(activity_el).data('id')){
            detail = DETAIL_ACTIVITY[i]
            break
        }
    }

    // Progress
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: `${BASE_URL}/activity/progress/${detail.ID_DETAIL_ACTIVITY}`,
        async: false,
        success: function(data){
            $('#detail-progress-tbody').empty()
            let total_progress = 0

            for(let i = 0; i < data.length; i++){
                total_progress += parseFloat(data[i].PROGRESS)
            }

            $('#progress-percentage-text').html(`${total_progress}%`)
            $('#progress-percentage-bar').css('width', `${total_progress}%`)
        }
    })

    if(detail.STATUS == 0){
        $('#progress_status').val('Belum dimulai')
    } else if(detail.STATUS == 1){
        $('#progress_status').val('On progress')
    } else if(detail.STATUS == 2){
        $('#progress_status').val('Terlambat')
    } else if(detail.STATUS == 3){
        $('#progress_status').val('Selesai')
    }

    $('#progress_nama_activity').val(detail.NAMA_AKTIFITAS)
    $('#progress-deadline').html(`${detail.DEADLINE.days} Hari lagi`)
    $('#progress-detail-btn').data('id', detail.ID_DETAIL_ACTIVITY)
    $('#progress-reschedule-btn').data('id', detail.ID_DETAIL_ACTIVITY)
    $('#progress-activity-modal').modal('show')
}

// Modal detail activity
function view_detail_activity(activity_el){
    $('#progress-activity-modal').modal('hide')
    var detail 

    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY == $(activity_el).data('id')){
            detail = DETAIL_ACTIVITY[i]
            break
        }
    }

    $('.detail_nama_activity').html(detail.NAMA_AKTIFITAS)
    $('#detail_tanggal').val(detail.TANGGAL_START +" - "+ detail.TANGGAL_END)
    $('#detail_pic').val(detail.PIC)

    // Log jadwal
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: `${BASE_URL}/activity/log-jadwal/${detail.ID_DETAIL_ACTIVITY}`,
        async: false,
        success: function(data){
            $('#log-jadwal-tbody').empty()

            for(let i = 0; i < data.length; i++){
                let tr =    `<tr style="color: black;">
                                <td>${i + 1}</td>
                                <td>${data[i].created_at}</td>
                                <td>${data[i].TANGGAL_START}</td>
                                <td>${data[i].TANGGAL_END}</td>
                            </tr>`

                $('#log-jadwal-tbody').append(tr)
            }
        }
    })
    
    // Progress
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: `${BASE_URL}/activity/progress/${detail.ID_DETAIL_ACTIVITY}`,
        async: false,
        success: function(data){
            $('#detail-progress-tbody').empty()
            let total_progress = 0
            let td_file

            for(let i = 0; i < data.length; i++){
                total_progress += parseFloat(data[i].PROGRESS)
                
                if(data[i].FILE != null){
                    td_file = `<a href="${BASE_URL}/admin/get-file/${data[i].ID_PROGRESS}" class="btn btn-sm btn-light" target="_blank">
                                <i class="fa fa-download mr-1"></i>
                                Download
                                </a>`
                } else {
                    td_file = 'Tidak ada file'
                }

                if(data[i].KETERANGAN == null){
                    data[i].KETERANGAN = 'Tidak ada keterangan'
                }

                let tr =    `<tr style="color: black;">
                                <td>${i + 1}</td>
                                <td>${data[i].created_at}</td>
                                <td>${data[i].PROGRESS}%</td>
                                <td>${data[i].KETERANGAN}</td>
                                <td>${td_file}</td>
                            </tr>`

                $('#detail-progress-tbody').append(tr)
            }

            $('#detail-percentage-text').html(`${total_progress}%`)
            $('#detail-percentage-bar').css('width', `${total_progress}%`)
        }
    })

    $('#detail-activity-modal').modal('show')
}

// Modal reschedule activity
function reschedule_activity(activity_el){
    $('#progress-activity-modal').modal('hide')
    var detail 

    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY == $(activity_el).data('id')){
            detail = DETAIL_ACTIVITY[i]
            break
        }
    }
    $('#reschedule-id-detail-activity').val(detail.ID_DETAIL_ACTIVITY)
    $('#reschedule-nama-activity').val(detail.NAMA_AKTIFITAS)
    $('#reschedule-tanggal-mulai').val(detail.TANGGAL_START)
    $('#reschedule-tanggal-selesai').val(detail.TANGGAL_END)
    
    let durasi = calculate_date_range(
        $('#reschedule-tanggal-mulai').val(),
        $('#reschedule-tanggal-selesai').val()
    )

    $('#reschedule-durasi').val(`${durasi} Hari`)
    $('#reschedule-modal').modal('show')
}

// Menghitung selisih antara dua tanggal
function calculate_date_range(start_date, end_date){
    let result = 'yooooo'

    if(start_date != '' && end_date != ''){
        start_date = new Date(start_date)
        end_date = new Date(end_date)

        let diff_in_time = end_date.getTime() - start_date.getTime()
        let diff_in_days = diff_in_time / (1000 * 3600 * 24)
        durasi = diff_in_days

        if(diff_in_days < 0){
            result = 'Tanggal mulai tidak boleh melebihi tanggal selesai'
        } else {
            result = diff_in_days + 1
        }

    } else {
        result = 'Tentukan tanggal mulai dan tanggal selesai'
    }

    return result
}

// Check perubahan tanggal mulai dan tanggal selesai pada modal reschedule
function reschedule_date_change(){
    let start_date = $('#reschedule-tanggal-mulai').val()
    let end_date = $('#reschedule-tanggal-selesai').val()
    let durasi = calculate_date_range(start_date, end_date)
    $('#reschedule-error-msg').css('display', 'none')
    if(durasi >= 0){
        $('#reschedule-durasi').val(`${durasi} Hari`)
    } else {
        $('#reschedule-durasi').val(durasi)
    }
}

function reschedule_form_check(){
    let form_status = 1
    let start_date = $('#reschedule-tanggal-mulai').val()
    let end_date = $('#reschedule-tanggal-selesai').val()
    let durasi = calculate_date_range(start_date, end_date)

    if(durasi >= 0){
        form_status = 1
    } else {
        $('#reschedule-error-msg').css('display', 'block')
        form_status = 0
    }

    if(form_status == 1){
        return true
    } else {
        return false
    }

}

// Menghapus semua isi timetable
function clear_timetable(id_outlet){
    let x = 0

    for(let i = 0; i < CATEGORY_ACTIVITY.length; i++){
        if(CATEGORY_ACTIVITY[i].ID_OUTLET == id_outlet){
            x++
        }
    }

    $('#timetable-tbody').empty()

    if(x == 0){
        let tr =    `<tr style="background-color: #fafafa;">
                        <td colspan="50">Data is empty</td>
                    </tr>`
        
        $('#timetable-tbody').append(tr)
    }

}

// Menghapus semua isi tabel tanggal-tanggal penting
function clear_tanggal_penting(id_outlet){
    let x = 0

    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_OUTLET == id_outlet && DETAIL_ACTIVITY[i].FLAG == 1){
            x++
        }
    }

    $('#tanggal-penting-tbody').empty()

    if(x == 0){
        let tr =    `<tr style="background-color: #fafafa;">
                        <td colspan="3">Data is empty</td>
                    </tr>`
        
        $('#tanggal-penting-tbody').append(tr)
    }
}
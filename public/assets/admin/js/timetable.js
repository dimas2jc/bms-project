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
}

function draw_category(id_outlet){
    for(let i = 0; i < CATEGORY_ACTIVITY.length; i++){
        if(CATEGORY_ACTIVITY[i].ID_OUTLET == id_outlet){
            put_category_into_table(CATEGORY_ACTIVITY[i].NAMA)
            draw_activity(CATEGORY_ACTIVITY[i].ID_CATEGORY)
        }
    }
}

function put_category_into_table(category_name){
    let tr =    `<tr style="background-color: #F4F4F7;">
                    <td style="text-align:left;">${category_name}</td>
                    <td></td>
                    <td colspan="48">${category_name}</td>
                </tr>
                `
    $('#timetable-tbody').append(tr)
}

function draw_activity(id_category){
    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_CATEGORY == id_category && DETAIL_ACTIVITY[i].FLAG == 0){
            put_activity_into_table(DETAIL_ACTIVITY[i])
        }
    }
}

function put_activity_into_table(activity){
    let empty_td = `<td></td>`
    let tr =    `<tr style="background-color: #FFFFFF;">
                    <td style="text-align:left; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="click to view detail"
                        data-id="${activity.ID_DETAIL_ACTIVITY}" onclick="view_detail_activity(this)">
                        ${activity.NAMA_AKTIFITAS}
                    </td>
                    <td>${activity.DURASI.d} Hari</td>
                    ${empty_td.repeat(48)}
                </tr>
                `
    $('#timetable-tbody').append(tr)
}

function draw_tanggal_penting(id_outlet){
    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_OUTLET == id_outlet && DETAIL_ACTIVITY[i].FLAG == 1){
            put_tanggal_penting_into_table(DETAIL_ACTIVITY[i])
        }
    }
}

function put_tanggal_penting_into_table(activity){
    let tr =    `<tr style="background-color: #FFFFFF;">
                    <td style="text-align:left; cursor: pointer;" data-toggle="tooltip" data-placement="top" title="click to view detail"
                        data-id="${activity.ID_DETAIL_ACTIVITY}" onclick="view_detail_activity(this)">
                        ${activity.NAMA_AKTIFITAS}
                    </td>
                    <td>${activity.TANGGAL_START}</td>
                    <td>${activity.TANGGAL_END}</td>
                </tr>`

    $('#tanggal-penting-tbody').append(tr)
}

function calculate_activity_duration(){
    let start_date = $('#input_tanggal_mulai').val()
    let end_date = $('#input_tanggal_selesai').val()

    if(start_date != '' && end_date != ''){
        start_date = new Date(start_date)
        end_date = new Date(end_date)

        let diff_in_time = end_date.getTime() - start_date.getTime()
        let diff_in_days = diff_in_time / (1000 * 3600 * 24)
        durasi = diff_in_days

        if(diff_in_days < 0){
            $('#input_durasi').val('Tanggal mulai tidak boleh melebihi tanggal selesai')
        } else {
            $('#input_durasi').val(`${diff_in_days} Hari`)
        }

    } else {
        $('#input_durasi').val('Tentukan tanggal mulai dan tanggal selesai')
    }
}

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

function view_detail_activity(activity_el){
    var detail 

    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY == $(activity_el).data('id')){
            detail = DETAIL_ACTIVITY[i]
            break
        }
    }

    $('#detail_outlet').val(detail.OUTLET)
    $('#detail_category').val(detail.CATEGORY)
    $('#detail_nama_activity').val(detail.NAMA_AKTIFITAS)
    $('#detail_tanggal_mulai').val(detail.TANGGAL_START)
    $('#detail_tanggal_selesai').val(detail.TANGGAL_END)
    $('#detail_durasi').val(`${detail.DURASI.d} Hari`)
    $('#detail_pic').val(detail.PIC)

    $('#detail-activity-modal').modal('show')
}

function clear_timetable(id_outlet){
    let x = 0

    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].ID_OUTLET == id_outlet && DETAIL_ACTIVITY[i].FLAG == 0){
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
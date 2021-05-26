$(function(){
    toastr.option = {
        showMethod: "slideLeft",
        hideMethod: "slidRight"
    }

    $('[data-toggle="tooltip"]').tooltip()
    $('select').select2()
})

function add_new_category(){
    let category_name = $('#input-new-category').val()
    toastr.remove()

    if(category_name == '' || category_name == null){
        toastr.error("Category name can't be empty")
    } else {
        create_new_category(category_name)
        $('#add-category-modal').modal('hide')
        toastr.success('New category added')
    }
}

function clear_add_category_modal(){
    $('#input-new-category').val('')
}

function create_new_category(category_name){
    let tr =    `<tr style="background-color: #F4F4F7;">
                    <td>${category_name}</td>
                    <td></td>
                    <td colspan="12">${category_name}</td>
                </tr>
                `
    $('#timetable-tbody').append(tr)
}
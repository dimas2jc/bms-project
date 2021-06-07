let timeline

$(function(){
    get_timeline(OUTLET[0].ID_OUTLET)
    draw_timeline(timeline)
})

/**
 * Function untuk request timeline activity
 */
function get_timeline(id_outlet){

    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: `${BASE_URL}/admin/activity/timeline`,
        async: false,
        data: {
            id_outlet: id_outlet
        },
        success: function(data){
            timeline = data
            console.log(timeline)
        }
    })
}

function draw_timeline(timeline){
    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].FLAG != 1){
            
            for(let j = 0; j < timeline.length; j++){
                if(timeline[j].ID_DETAIL_ACTIVITY == DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY){
                    let el_tr_selector = `tr_${DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY}`
                    let timeline_content = timeline[j].TIMELINE.WEEKS

                    for(let k = 0; k < timeline_content.length; k++){
                        let el_td_selector = `week-${timeline_content[k]}`
                        $(`#${el_tr_selector} .${el_td_selector}`).css('background-color', 'green')
                        // $(`#${el_tr_selector} .${el_td_selector}`).css('border', '0')
                    }
                }
            }
        }
    }
}
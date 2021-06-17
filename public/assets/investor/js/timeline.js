$(function(){
    get_timeline(OUTLET[0].ID_OUTLET, $('#tahun-aktif').val())
    draw_timeline(timeline)
})

/**
 * Function untuk request timeline activity
 */
function get_timeline(id_outlet, tahun){

    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        url: `${BASE_URL}/admin/activity/timeline`,
        async: false,
        data: {
            id_outlet: id_outlet,
            tahun: tahun
        },
        success: function(data){
            timeline = data
            console.log(timeline)
        }
    })
}

function draw_timeline(timeline){
    var timeline_color = '#7a7a7a'

    for(let i = 0; i < DETAIL_ACTIVITY.length; i++){
        if(DETAIL_ACTIVITY[i].FLAG != 1){
            
            // for(let j = 0; j < timeline.length; j++){
            //     if(timeline[j].ID_DETAIL_ACTIVITY == DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY && timeline[j].TIMELINE.WEEKS.length > 0){
            //         let el_tr_selector = `tr_${DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY}`
            //         let timeline_content = timeline[j].TIMELINE.WEEKS

            //         for(let k = 0; k < timeline_content.length; k++){
            //             let el_td_selector = `week-${timeline_content[k]}`
            //             $(`#${el_tr_selector} .${el_td_selector}`).css('background-color', `${timeline[j].TIMELINE.COLOR}`)
            //         }
            //     }
            // }

            for(let j = 0; j < timeline.length; j++){
                if(timeline[j].ID_DETAIL_ACTIVITY == DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY && timeline[j].TIMELINE.length > 0){
                    let el_tr_selector = `tr_${DETAIL_ACTIVITY[i].ID_DETAIL_ACTIVITY}`

                    for(let k = 0; k < timeline[j].TIMELINE.length; k++){
                        let timeline_content = timeline[j].TIMELINE[k]

                        if(k == (timeline[j].TIMELINE.length - 1)){
                            timeline_color = timeline[j].TIMELINE_COLOR
                        } else {
                            timeline_color = '#7a7a7a'
                        }

                        for(let l = 0; l < timeline_content.length; l++){
                            let el_td_selector = `week-${timeline_content[l]}`
                            $(`#${el_tr_selector} .${el_td_selector}`).css('background-color', `${timeline_color}`)
                        }

                    }

                }
            }

        }
    }
}
$(function(){
    var timeline

    $.ajax({
        type: 'GET',
        url: `${BASE_URL}/admin/activity/timeline`,
        async: false,
        success: function(data){
            timeline = data[0]
        }
    })

    console.log(timeline)
})
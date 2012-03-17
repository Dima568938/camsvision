$(function() {
    if(VK==null){
       // VK.init({apiId: 2692158 });
        //alert("sfsgsdgdfgsdf");
    }
    resizeWindow();
   // VK.Widgets.Comments('vk_comments', {limit: 30, width: '470px', attach: '*', pageUrl:"<?=Yii::app()->request->serverName.'/?id='.$model->id?>" , onChange: function(data){//addActivity(1)
    //}});
});

function resizeWindow(){
  /*  x = 827;
    y = $("#footer").position().top+$("#footer").height()+
            parseInt($("#footer").css("margin-top"))+parseInt($("#footer").css("margin-bottom"))+
            parseInt($("#footer").css("padding-bottom"))+parseInt($("#footer").css("padding-bottom"));
    VK.callMethod("resizeWindow", x, y);
    setTimeout('resizeWindow()', 1000);*/

}

function addActivity(res){

    alert('321');
}

function locationChange(address){
    window.location.assign(address);
}


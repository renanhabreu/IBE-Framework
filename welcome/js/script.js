$(document).ready(function(){
    $('.skt-description').addClass('ui-state-highlight');
    
    $("#skt-menu").accordion({
        fillSpace: true
    });
    
    $("button").button();
    
    $("button").click(function(){
        var btn = $(this);
        var maker = $("#maker-"+btn.attr('id'));
        var action = $(".role",maker).attr('role');
        
        btn.button("disable");
        $.each($('input',maker),function(i,elm){
            var el = $(elm);
            var vl = el.val();
            var name = el.attr('name');
            vl = (vl == "")? "default":escape(vl);
            action = action+"/"+name+"/"+vl; 
            
            $('input:[name='+name+']').val(vl);
        });
        
        $.getJSON("../skt/ibe.php/"+action, function(response){
            var html = "";
            var alert = $("#skt-menu-alert-content").find("p");
            for(var i in response.message){
                html += response.message[i];
            }
            alert.text("");
            alert.html(html);
        }).complete(function(){
            btn.button("enable");
        });
        
    })
});




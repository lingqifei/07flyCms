$(document).ready(function () {
    $('.chosen-select.customer').each(function(){
        var value  =$(this).val();
        var target =$(this).attr("data-url");
        log(value);
        findCustomerLinkSelect(value,target)
    });
    //选择用户跳出联系人
    $('.chosen-select.customer').on('change', function (e, params) {
        var value  =$(this).val();
        var target =$(this).attr("data-url");
        log(value);
        findCustomerLinkSelect(value,target)
    });
});

function findCustomerLinkSelect(cid,target=null){
    //得到地址
    $('.chosen-select.linkman').each(function(){
        var that	=$(this);
        var val=that.attr('data-val');
        $.ajax({
            type: "POST",
            url: target,
            data:{"customer_id":cid,"customer_type":'linkman'},
            dataType:"json",
            async:false,
            beforeSend : function(){
                that.empty();
            },
            success: function(jsondata){
                var html = '';
                $.each(jsondata.data, function(idx, obj) {
                    html +='<option value="'+obj.id+'" >'+obj.name+'</option>';
                });
                that.append(html);
                //that.trigger('chosen:updated');
                log(val);
                that.val(val).trigger("chosen:updated");
            },
            complete: function () {
                that.val(val).trigger("chosen:updated");
            }
        });
    });

    //得到地址
    $('.chosen-select.chance').each(function(){
        var that	=$(this);
        var val=that.attr('data-val');
        $.ajax({
            type: "POST",
            url: target,
            data:{"customer_id":cid,"customer_type":'chance'},
            dataType:"json",
            async:false,
            beforeSend : function(){
                that.empty();
            },
            success: function(jsondata){
                var html = '';
                $.each(jsondata.data, function(idx, obj) {
                    html +='<option value="'+obj.id+'" >'+obj.name+'</option>';
                });
                //log(html);
                that.append(html);
                log(val);
                //that.trigger('chosen:updated');
                that.val(val).trigger("chosen:updated");
            },
            complete: function () {
                that.val(val).trigger("chosen:updated");
            }
        });
    });

}
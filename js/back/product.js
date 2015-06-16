/**
 * Scripts for js tree
 */

$(document).ready(function () {
    $("#tree").bind("loaded.jstree", function (event, data) {
        /*data.inst.get_container().find('li').each(function (i) {
            if (data.inst.get_path($(this)).length == 1) {
                data.inst.open_node($(this));
            }
        });*/
    }).delegate("a", "click", function (event) {
	// On link click get parent li ID and redirect to find action
        var id_group=$(this).parent("li").attr('id').replace('treeNode_', '');
        $.ajax({
            type: "POST",
            url: "/admin/product/findGroup",
            data:{
                id:id_group
            },
            success: function(response) {
                var group_obj=JSON.parse(response);
                $("#Product_group").val(group_obj["name"]);
                $("#Product_product_group_id").val(group_obj["id"]);
            }
        });
    });
    
    /*
    // Close all open nodes if was opened current
    .bind("open_node.jstree", function (e, data) {
	data.rslt.obj.siblings(".jstree-open").each(function () { 
            data.inst.close_node(this, true); 
        }); 
    })*/
});
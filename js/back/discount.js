/**
 * Scripts for js tree
 */

$(document).ready(function () {
    $("#tree").bind("loaded.jstree", function (event, data) {}).delegate("a", "click", function (event) {
	// On link click get parent li ID and redirect to find action
        var id_group=$(this).parent("li").attr('id').replace('treeNode_', '');
        $.ajax({
            type: "POST",
            url: "/admin/discount/findGroup",
            cache:false,
            data:{
                id:id_group
            },
            success: function(response) {
                var group_obj=JSON.parse(response);
                var products=group_obj["products_select"];
                $("#Product_group").val(group_obj["name"]);
                $("#Product_name").empty();
                if(products!==null){
                    for (var key in products) {
                        $('#Product_name').append('<option value="' + key + '">' +products[key]+ '</option>');
                    }
                }
            }
        });
    });
    
  
});



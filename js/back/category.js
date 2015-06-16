/**
 * Scripts for js tree
 */

$(document).ready(function () {
    $("#tree").bind("loaded.jstree", function (event, data) {
        //data.inst.open_all(-11);
    }).delegate("a", "click", function (event) {
	// On link click get parent li ID and redirect to category update action
	var id = $(this).parent("li").attr('id').replace('treeNode_', '');
	window.location = '/admin/category/edit/id/' + id;
    }).bind("move_node.jstree", function (e, data) {
	data.rslt.o.each(function (i) {
            $.ajax({
                async : false,
                type: 'POST',
                url: "/admin/category/moveNode",
                data : {
                    "id" : $(this).attr("id").replace('treeNode_',''),
                    "ref" : data.rslt.cr === -1 ? 1 : data.rslt.np.attr("id").replace('treeNode_',''),
                    "position" : data.rslt.cp + i
                },
                success: function(response){
                    alertify.success(response);
                    window.location.reload(true);
                }
            });
	});
    })
});
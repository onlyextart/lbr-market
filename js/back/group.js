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
	// On link click get parent li ID and redirect to category update action
	var id = $(this).parent("li").attr('id').replace('treeNode_', '');
	window.location = '/admin/group/edit/id/' + id;
    }).bind("move_node.jstree", function (e, data) {
	data.rslt.o.each(function (i) {
            $.ajax({
                async : false,
                type: 'GET',
                url: "/admin/group/moveNode",
                data : {
                    "id" : $(this).attr("id").replace('treeNode_',''),
                    "ref" : data.rslt.cr === -1 ? 1 : data.rslt.np.attr("id").replace('treeNode_',''),
                    "position" : data.rslt.cp + i
                }
            });
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
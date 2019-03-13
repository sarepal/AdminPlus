<?php

class AdminPlusPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
    	'define_acl',
    	'uninstall',
    	'admin_users_form'
    	);


    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];


        // AdminPlus inherit the rights of Admins...
		$acl->addRole(new Zend_Acl_Role('adminplus'), 'admin');
		// ... but are able to access Appearance and Themes
		$acl->allow('adminplus', array('Appearance', 'Themes'));

    }

    public function hookAdminUsersForm($args){
			// reorder and annotate the Role dropdown menu choices and update the explanatory text.
			?>
			<script type="text/javascript">
				var betterOrder=['researcher','contributor','admin','adminplus','super'];
				var selected = jQuery("#role").val();
				resortedRoles=[]
				betterOrder.forEach(function(name){
					var note=(name=='adminplus') ? ' (AdminPlus plugin)' : '';
					var opt='<option value="'+name+'">'+name.charAt(0).toUpperCase()+ name.slice(1)+note+'</option>';
					resortedRoles.push(opt);
				});
				var parser = document.createElement('a');
				parser.href = jQuery(location).attr('href');
				var slug=parser.pathname;
				jQuery("#role").empty().append( resortedRoles );
				if(slug.indexOf('edit')){
					jQuery("#role").val(selected);
					}
				jQuery('#role-label ~ div p').append(' See also <a href="https://github.com/sarepal/AdminPlus" target=_blank">AdminPlus plugin documentation</a>.');
			</script>
			<?php
    }


    public function hookUninstall(){
	    // Upon uninstalling the plugin, revert the roles of AdminPlus back to Admin
        $db = $this->_db;
        $sql = "UPDATE `omeka_users` SET `role`='admin' WHERE `role`= 'adminplus' ";
        $db->query($sql);
    }

}

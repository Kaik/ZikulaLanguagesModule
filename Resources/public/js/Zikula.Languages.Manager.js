var Zikula = Zikula || {};
Zikula.LanguagesManager = Zikula.LanguagesManager || {};
Zikula.LanguagesManager.Admin = {};

( function($) {
	
    var modal;    
        
    Zikula.LanguagesManager.Admin.init = function ()
    { 
        
        console.log('stage: 0');
    	
        modal = $("#languages_manager_modal");
        
        $("#language_manager_add_language").click(function(e) {
    		e.preventDefault();
    	          Zikula.LanguagesManager.Admin.newLanguage();
    	});
        
        $("#language_manager_edit_language").click(function(e) {
    		e.preventDefault();
    	          console.log('Language edit settings form');
    	});

    };
    
    Zikula.LanguagesManager.Admin.newLanguage = function ()
    {
        var pars = {};
    	
        $.ajax({
            type: "GET",
            url: Routing.generate('zikulalanguagesmodule_admin_newlanguage'),
            data: pars
        }).success(function(result) {	
                var html = result;
                modal.find('.modal-title').html('Add new language');
                modal.find('.modal-body').html(html);
                modal.modal("show");
       	 	//manager.view.itemEdit( template ); 
        }).error(function(result) {
       	 	//manager.view.displayError( result.status + ': ' + result.statusText);
        }).always(function() {
    		//manager.view.hideBusy();           
        });   
    };
        
    $(document).ready(function() {
        Zikula.LanguagesManager.Admin.init();
    });
})(jQuery);
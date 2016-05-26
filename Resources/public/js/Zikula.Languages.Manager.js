var Zikula = Zikula || {};
Zikula.Languages = Zikula.Languages || {};
Zikula.Languages.Manager = Zikula.Languages.Manager || {};

/**
 * Languages manager
 */

(function (manager, $, undefined) {
    /*
     * manager properties 
     * 
     */
    //Private Property


    //Public Property
    manager.core = false;
    manager.installed = false;
    manager.dir_access = false;
    /*
     * manager functions  
     * 
     */
    manager.init = function (config) {
        //add vars from config
        manager.core = config.core;
        manager.installed = config.installed;
        manager.dir_access = config.dir_access;
        // view as singelton init
        manager.view = view.getInstance();  	
        //load data true ajax false view
        loadData(false);
        console.log('Zikula.Languages.Manager initiated');
        console.log(Zikula.Languages);
        console.log(config);
    };
   
    manager.editLanguage = function(language_code) {
        var pars = {'language_code':language_code};
            console.log('zzz');
        $.ajax({
            type: "GET",
            url: Routing.generate('zikulalanguagesmodule_admin_editlanguage'),
            data: pars
        }).success(function (result) {
            var html = result;
            manager.view.setModalTitle('Edit language');
            manager.view.setModalContent(html);
            manager.view.setModalButtonSave(language_code);                       
            manager.view.openModal();
        }).error(function (result) {
            //manager.view.displayError( result.status + ': ' + result.statusText);
             //manager.view.openModal();
        }).always(function () {
            //manager.view.hideBusy();           
        });
        
        
    };    
    
    manager.saveLanguage = function(language_code) {
                
        $.ajax({
            type: "POST",
            url: Routing.generate('zikulalanguagesmodule_admin_editlanguage'),
            data: $('form[name="zikulalanguagesmodule_languagetype"]').serialize() + "&language_code=" + language_code
        }).success(function (result) {
            var html = result;
            console.log(html);                  
            manager.view.closeModal();
            
        }).error(function (result) {
            //manager.view.displayError( result.status + ': ' + result.statusText);
             //manager.view.openModal();
        }).always(function () {
            //manager.view.hideBusy();           
        });
        
        
    };  
          
    manager.removeLanguage = function(language_code) {
        var pars = {'language_code':language_code};
            
        $.ajax({
            type: "GET",
            url: Routing.generate('zikulalanguagesmodule_admin_removelanguage'),
            data: pars
        }).success(function (result) {
            var html = result;
            console.log(html);
        }).error(function (result) {
            //manager.view.displayError( result.status + ': ' + result.statusText);
             //manager.view.openModal();
        }).always(function () {
            //manager.view.hideBusy();           
        });   
    };     
    

    /*
     * manager config  
     * 
     */
    function loadData(useAjax) {

        if (useAjax) {
            //console.log('load data using ajax');   		
        } else {
            //console.log('load data from view');
            manager.view.getDataFromView();
        }
        console.log('Zikula.Languages.Manager data loaded');    
    };

    /*
     * manager.view
     */
    var view = (function () {

        // Instance stores a reference to the Singleton
        var instance;

        function Init() {
            /*
             * manager.view properties
             */
            //container
            var $manager = $('#languages_manager');
            //modal
            var $modal = $('#languages_manager_modal');
            /*
             * manager.view init
             */
            //start listening for actions
            bindViewEvents();
            
            
            console.log('Zikula.Languages.Manager.view initialised');
            
            /*
             * manager.view functions 
             */
            function bindViewEvents() {
                /* bind add language event */
                $manager.find('a.add_language').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        manager.editLanguage(false);
                    });
                });
                /* bind add language event */
                $manager.find('a.edit_language').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        manager.editLanguage($(this).data('languagecode'));
                    });
                });
                /* bind add language event */
                $manager.find('a.remove_language').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        manager.removetLanguage($(this).data('languagecode'));
                    });
                });
              
                console.log('Zikula.Languages.Manager.view events binded');
            }

            /*
             * manager.view functions 
             * Data
             */
            function getDataFromView() {


            }

            //modal
            function openModal() {
                $modal.modal('show');
            }
            function closeModal() {
                $modal.modal('hide');
            }
            function setModalTitle(html) {
                $modal.find('.modal-title').html(html);
            } 
            function setModalContent(html) {
                $modal.find('.modal-body').html(html);
            }
            function setModalFooter(html) {
                $modal.find('.modal-footer').html(html);
            } 
            function setModalButtonSave(language_code) {
                var $buttonSave = $modal.find("button.save");
                $buttonSave.click(function (e) {
                    e.preventDefault();
                    manager.saveLanguage(language_code);
                });
                $buttonSave.removeClass('hide');
            }
            
            //overlay
            function getOverlay() {
                //return $("<div id='overlay'><i class='fa fa-circle-o-notch fa-spin'></i></div>");
            }
            function removeOverlay() {
                //$('#overlay').remove();
            }

            // busy
            function showBusy() {
                //$('#kmgallery_manager').append(getOverlay());
            }
            function hideBusy() {
                //$('#overlay').remove();
            }
            
            //errors
            function displayError(html) {

            }
            ;

            /*
             * manager.view public
             * 
             */
            return {
                openModal: openModal,
                closeModal: closeModal,
                setModalTitle: setModalTitle,
                setModalContent: setModalContent,
                setModalFooter: setModalFooter,
                setModalButtonSave: setModalButtonSave,  
                getDataFromView: getDataFromView,
                showBusy: showBusy,
                hideBusy: hideBusy,
                displayError: displayError
            };
        }
        ;

        return {
            // Get the Singleton instance if one exists
            // or create one if it doesn't
            getInstance: function () {
                if (!instance) {
                    instance = Init();
                }
                return instance;
            }
        };

    })();

}(Zikula.Languages.Manager, jQuery));





















/*
 
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
 
 };
 
 Zikula.LanguagesManager.Admin.createLanguage = function (language)
 {
 var pars = {'language': language};
 
 $.ajax({
 type: "POST",
 url: Routing.generate('zikulalanguagesmodule_admin_createlanguage'),
 data: pars
 }).success(function(result) {	
 var json = result;
 console.log(json);
 //modal.find('.modal-title').html('Add new language');
 modal.find('.modal-body').html('<div class="alert alert-success"> Language '+ json.language +' created! </div>');
 //modal.find('.modal-footer').html('<button type="button" id="language_manager_save_language" class="btn btn-success">Add</button>');
 //$("#language_manager_save_language").click(function(e) {
 //        e.preventDefault();
 //        var selected = $('#zikulalanguagesmodule_languagetype_mlsettings_multilingual').val();
 //       console.log(selected);
 //});                                
 //modal.modal("show");
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
 
 */
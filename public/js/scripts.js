/**
 * Check or uncheck all cehckboxes
 * 
 * @param {bool} check_all
 * @return {void}
 */
function toggleCheckboxes()
{
    $('#check_all').change(function(){
        var checkboxes = $(this).closest('form').find(':checkbox');
        if($(this).is(':checked')) {
            checkboxes.prop('checked', true);
        } else {
            checkboxes.prop('checked', false);
        }
    });
}

/**
 * Enable or disable the action buttons
 * 
 * @param {bool} enable
 * 
 * @return {void}
 */ 
function toggleEnableActionButtons(enable)
{
    if(enable === true){
        $('#btn-trash').removeAttr('disabled');
    }else{
        $('#btn-trash').attr('disabled', 'disabled');
    }
}

/**
 * Scan all checkboxes on document searching for one checked
 * 
 * @param {string} targets css selector
 * 
 * @return {bool}
 */ 
function scanCheckedCheckboxes(targets)
{
    var checked = false;

    $(targets).each(function() {
        
        if($(this).prop('checked')){
            $(this).closest('tr').addClass('row-table-selected');
            checked = true;
        }else{
            $(this).closest('tr').removeClass('row-table-selected');
        }
        
    });
    
    toggleEnableActionButtons(checked);

    return checked;
}

/**
 * Check or uncheck checkbox when a table row is clicked
 * 
 * @param {object} row
 * @param {object} event
 * 
 * @return void
 */ 
function toggleCheckboxFromRowClick()
{
    
    $('tbody tr').click(function(event){
        //toggleCheckboxFromRowClick($(this), event);
       var checkbox = $(this).find('input[type=checkbox]').attr('id');
        // if is not a check box and is not a link then...
        if (event.target.type !== 'checkbox' && event.target.nodeName !== 'A'){
            $('#'+checkbox).trigger('click');
        } 
        
    });

    scanCheckedCheckboxes('.checkbox-table-item');
}
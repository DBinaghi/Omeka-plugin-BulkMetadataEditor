<?php 
/**
 * Sedmeta main admin form
 *
 * This Omeka curator form collects information defining a set of 
 * bulk edits to perform on the omeka database. It includes 
 * functionality to perform the changes, and to return preview 
 * of the affected records.
 *
 * @copyright Copyright 2014 UCSC Library Digital Initiatives
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Sedmeta main admin form class
 *
 * This Omeka curator form collects information defining a set of 
 * bulk edits to perform on the omeka database. It includes 
 * functionality to perform the changes, and to return preview 
 * of the affected records.
 *
 */

class SedMeta_Form_Main extends Omeka_Form
{
   /**
     * Initialize the form.
     */
    public function init()
    {
        parent::init();

        $this->setAttrib('id', 'sedmeta-form');
        $this->setMethod('post');

        $this->_registerElements();


	
    }

    private function _registerElements()
    {
      $this->addElement('hidden',"callback",array("value"=>""));

      $this->addElement('select','sedmeta-collection-id',array(
            'label'         => __('Choose Collection'),
            'description'   => __('Edit items from this collection'),
            'value'         => '0',
	    'order'         => 1,
            'required'      => true,
	    'multiOptions'       => $this->_getCollectionOptions()
							      )
		       );


      $this->addElement('checkbox', 'item-select-meta', array(
            'label'         => __('Select Items by Metadata'),
	    'id' => 'item-select-meta',
            'description'   => __('Select items to edit based on their associated metadata elements'),
	    'order'         => 2
							      )
			  );
      //todo: add custom zend form element for my fancy shmancy thingy
      /*
<div id="item-meta-selects" style="display:none;">
   <div class="field" id="item-meta-select">
   <p>Which also meet the following criteria: (use * as a wildcard character)</p>
   <div id="item-rule-boxes">
   <div id="item-rule-box" class="item-rule-box" style="clear:left;">
   <div class="inputs three columns alpha">
   <?php echo $this->formSelect('sedmeta-element-id', '50', array('class' => 'sedmeta-element-id'), $this->form_element_options) ?>
   </div>
   <div class="inputs two columns beta">
   <?php echo $this->formSelect('sedmeta-compare', null, array('class' => 'sedmeta-compare'), $this->form_compare_options) ?>
   </div>
   <div class="inputs three columns omega">
   <?php echo $this->formText('sedmeta-selector',"Input search term here",array('class'=>'sedmeta-selector')) ?>
   </div>
  <div class="removeRule">[x]</div>
   <div class="field">
   <div class="inputs two columns omega">
  <?php echo $this->formCheckbox('sedmeta-case',"Match Case",array('class'=>'sedmeta-case','checked'=>'checked')) ?><label for="sedmeta-case"> Match Case </label>
   </div>
   </div>
   </div>	     
   </div>
   </div> 
   <div class="field">
   <button id="add-rule">Add Another Rule</button>
   </div>
   </div>

       */
      


      $this->addElement('button', 'preview-items-button', array(
	    'label'=>'Preview Selected Items',
	    'id' => 'preview-items-button',
	    'order'         => 3
							      )
			);

      $this->addElement('button', 'hide-item-preview', array(
	    'label'=>'Hide Item Preview',
	    'id' => 'hide-item-preview',
	    'order'         => 4
							      )
			);


      //todo: add custom zend form element for empty div here

      $this->addElement('select', 'selectfields[]', array(
							'label'         => __('Select elements to edit'),
							'description'   => __('Select the metadata elements you would like to edit. (default: all)'),
							'size'=>10,
							'order'         => 5,
							'multiOptions'       => $this->_getElementOptions()
							)
			);
      
      $this->addElement('button', 'preview-fields-button', array(
	    'label'=>'Preview Selected Fields',
	    'id' => 'preview-items-button',
	    'order'         => 6
							      )
			);

      $this->addElement('button', 'hide-field-preview', array(
	    'label'=>'Hide Field Preview',
	    'id' => 'hide-field-preview',
	    'order'         => 7
							      )
			);


      //todo: add custom zend form element for empty div here

 $this->addElement('radio', 'changes-radio', array(
            'label'         => __('Define Edits'),
            'description'   => __('Choose the type of edit you would like to perform'),
	    'order'         => 8,
	    'multiOptions'       => array(
					  'replace'=>'Search and replace text (within any metadata in the selected fields on the selected items)',
					  'add'=>'Add a new metadatum in the selected field',
					  'append'=>'Append text to existing metadata in the selected fields',
					  'delete'=>'Delete all existing metadata in the selected fields'	  
					  )
							   )
			  );

 
      $this->addElement('button', 'preview-changes-button', array(
	    'label'=>'Preview Changes',
	    'id' => 'preview-changes-button',
	    'order'         => 9
							      )
			);

      $this->addElement('button', 'hide-changes-preview', array(
	    'label'=>'Hide Change Preview',
	    'id' => 'hide-changes-preview',
	    'order'         => 10
							      )
			);

      //The following elements will be re-ordered in javascript

      $this->addElement('text','sedmeta-search', array(
	        'label'=>'Search for:',
		'id'=>'sedmeta-search',
		'class'=>'sedmeta-hidden',
		'description'=>'Input text you want to search for '
						       )
			);
      $this->addElement('text','sedmeta-replace', array(
	        'label'=>'Replace with:',
		'id'=>'sedmeta-replace',
		'class'=>'sedmeta-hidden',
		'description'=>'Input text you want to replace with '
						       )
			);
      $this->addElement('checkbox','regexp', array(
	        'description'=>'Use regular expressions',
		'id'=>'regexp',
		'class'=>'sedmeta-hidden',
		'value'=>'true'
						       )
			);
      $this->addElement('text','sedmeta-add', array(
	        'label'=>'Text to Add',
		'id'=>'sedmeta-add',
		'class'=>'sedmeta-hidden',
		'description'=>'Input text you want to add as metadata'
						       )
			);
      $this->addElement('text','sedmeta-append', array(
	        'label'=>'Text to Append',
		'id'=>'sedmeta-append',
		'class'=>'sedmeta-hidden',
		'description'=>'Input text you want to append to metadata'
						       )
			);



      
      $this->addDisplayGroup(array(
				   'sedmeta-collection-id', 
				   'item-select-meta',
				   'preview-items-button',
				   'hide-item-preview'
				   ), 'sedmeta-items-set');
      
      $this->addDisplayGroup(array( 
				   'selectfields[]',
				   'preview-fields-button',
				   'hide-field-preview'
				   ), 'sedmeta-fields-set');
      
      $this->addDisplayGroup(array( 
				   'changes-radio',
				   'preview-changes-button',
				   'hide-changes-preview'
				   ), 'sedmeta-fields-set');

    }

    /**
     * Get an array to be used in 'select' elements containing all collections.
     * 
     * @param void
     * @return array $collectionOptions Array of all collections and their
     * IDs, which will be used to populate a dropdown menu on the main view
     */
    private function _getCollectionOptions()
    {
      $collections = get_records('Collection',array(),'0');
      $options = array('0'=>'All Collections');
      foreach ($collections as $collection)
	{
	  $titles = $collection->getElementTexts('Dublin Core','Title');
	  if(isset($titles[0]))
	    $title = $titles[0];
	  $options[$collection->id]=$title;
	}

      return $options;
    }

/**
     * Get an array to be used in html select input
 containing all elements.
     * 
     * @param void
     * @return array $elementOptions Array of options for a dropdown
     * menu containing all elements applicable to records of type Item
     */
    private function _getElementOptions()
    {
        $db = get_db();
        $sql = "
        SELECT es.name AS element_set_name, e.id AS element_id, 
        e.name AS element_name, it.name AS item_type_name
        FROM {$db->ElementSet} es 
        JOIN {$db->Element} e ON es.id = e.element_set_id 
        LEFT JOIN {$db->ItemTypesElements} ite ON e.id = ite.element_id 
        LEFT JOIN {$db->ItemType} it ON ite.item_type_id = it.id 
         WHERE es.record_type IS NULL OR es.record_type = 'Item' 
        ORDER BY es.name, it.name, e.name";
        $elements = $db->fetchAll($sql);
        $options = array();
	//        $options = array('' => __('Select Below'));
        foreach ($elements as $element) {
            $optGroup = $element['item_type_name'] 
                      ? __('Item Type') . ': ' . __($element['item_type_name']) 
                      : __($element['element_set_name']);
            $value = __($element['element_name']);
            
            $options[$optGroup][$element['element_id']] = $value;
        }
        return $options;
    }
}
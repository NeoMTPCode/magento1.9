class Pfay_Films_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
     //indicate where we can find the controller
     $this->_controller = 'adminhtml_films';
     $this->_blockGroup = 'films';
     //header text
     $this->_headerText = 'Manage my movies;
     //button label
     $this->_addButtonLabel = 'Add a movie';
     parent::__construct();
     }
}
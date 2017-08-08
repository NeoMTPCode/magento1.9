<?php
class Magentotutorial_Weblog_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function testModelAction() {
        echo 'Setup!';
    }

    public function testModel2Action() {
        $params = $this->getRequest()->getParams();
        $blogpost = Mage::getModel('weblog/blogpost');
        echo("Loading the blogpost with an ID of ".$params['id']);
        $blogpost->load($params['id']);

        $data = $blogpost->getData();
        var_dump($data);
    }

    public function createNewPostAction() {
        $blogpost = Mage::getModel('weblog/blogpost');
        $blogpost->setTitle('Code Post!');
        $blogpost->setPost('This post was created from code!');
        $blogpost->save();
        echo 'post with ID ' . $blogpost->getId() . ' created';
    }
    public function editFirstPostAction() {
        $blogpost = Mage::getModel('weblog/blogpost');
        $blogpost->load(1);
        $blogpost->setTitle("The First post!");
        $blogpost->save();
        echo 'post edited';
    }
    public function deleteFirstPostAction() {
        $blogpost = Mage::getModel('weblog/blogpost');
        $blogpost->load(1);
        $blogpost->delete();
        echo 'post removed';
    }
    public function showAllBlogPostsAction() {
        $posts = Mage::getModel('weblog/blogpost')->getCollection();
        foreach($posts as $blogpost){
            echo '<h3>'.$blogpost->getTitle().'</h3>';
            echo nl2br($blogpost->getPost());
        }
    }

    public function saveAction()
    {
        //we get the datas sent with POST
        $title = ''.$this->getRequest()->getPost('title');
        $post = ''.$this->getRequest()->getPost('post');

        //we check that the name is not empty
        if( isset($title) && ($title!='') )
        {
            //we create an entry in the database
            $blog = Mage::getModel('weblog/blogpost');
            $blog->setData('title',$title);
            $blog->setData('post',$post);
            $blog->save();

            //we create an entry in the database
            // but this time we will use setName ( the method exist automatically in magento for every attribute with their name setXXX where XXX is the name of your attribute).
//            $contact = Mage::getModel('weblog/blogpost');
//            $contact->setName('Gladiator 3');
//            $contact->save();
        }

        //we redirect the user to the index method of the indexController
        // of our module films
        $this->_redirect('weblog/index/index');
    }
}
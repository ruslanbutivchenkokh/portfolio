<?php

class Content_BannersController extends Application_Model_AbstractPaginationController
{

    private $mBanners;

    public function init()
    {
        $mAppClientDescription = new Application_Model_AppClientDescription();
        $this->clientName = $mAppClientDescription->getClientName();

        $this->view->headTitle($this->clientName . ' : Admin');
        $this->session = new Zend_Session_Namespace();
        $this->mUser = new Application_Model_User($this->session->userId);
        $this->mClientDescription = new Application_Model_AppClientDescription();

        $this->mBanners = new Application_Model_Banners();
        $this->initialisePagination($this->getRequest(), $this->mBanners);

        if (($this->mUser->getType() != 'TYPE_ADMIN') && ($this->mUser->getType() != 'TYPE_SUPERADMIN')) {
            throw new Exception_Forbidden('Access Forbidden');
        }

        if (!$this->session->activeModules[$this->getRequest()->getModuleName()]) {
            throw new Exception_Forbidden('Access Forbidden');
        }

        $this->_helper->viewRenderer->setNoRender(true);
        $this->layout = Zend_Layout::getMvcInstance();
    }

    public function indexAction($saved = null)
    {

        if ($this->session->userId) {

            $this->layout->assign('main', $this->view->partial('banners/index.phtml', array(
                'pagination_object' => $this->getTemplateParameters(),
            )));

            $this->view->headScript()->appendFile('js/pages/banners/banners.js');
        }

    }

    public function searchBannersAction($searchTerm=null, $pageNumber=0)
    {

        $this->_helper->viewRenderer->setNoRender(true);

        $paginationLimit = $this->mClientDescription->getPaginationLimit();

        $listOfBannersItems = $this->mBanners->getListOfBanners($searchTerm,$pageNumber,$paginationLimit);

        $Bannerscount = $this->mBanners->Bannerscount($searchTerm);

        $modules='';

        $this->_helper->json(array(
            'users'      => $listOfBannersItems,
            'modules'    => $modules,
            'usersCount' => $Bannerscount,
            'paginationLimit' => $paginationLimit,
            'pagination' => $this->getAjaxParameters()));

    }

    public function ajaxSaveBannerAction($mediaimage, $title, $url, $banner_id = null)
    {

        $this->_helper->layout->disableLayout();

        $mBanners = new Application_Model_Banners();
        if (!empty($banner_id)) {
            $mBanners->load($banner_id);
        }
        $mContentKeywords = new Application_Model_ContentKeywords();
        $keywordList = $mContentKeywords->getKeywordList();
        $blacklist = explode(',', $keywordList['BlackListKeywords']);

        foreach ($blacklist as $keywords) {
            if (strripos($title, trim($keywords)) !== false) {
                $return = new stdClass();
                $return->success = false;
                $return->errorMessage = "Title contain a black listed word";
                $return->data = array('id' => $banner_id);
                $this->_helper->json($return);
                die();
            }
        }

        $mBanners->setTitle($title);
        $mBanners->setUrl($url);
        $mBanners->setImage($mediaimage);
        $mBanners->save();


        $return = new stdClass();
        $return->success = true;
        $return->errorMessage = "";
        $return->data = array('id' => $banner_id, 'title' => $title, 'fileName' => $mediaimage);

        $this->_helper->json($return);
        die();
    }

    public function ajaxChangeBannerStatusAction($banner_id, $status)
    {
        $this->_helper->layout->disableLayout();

        $mBanners = new Application_Model_Banners();
        $mBanners->load($banner_id);

        if($mBanners::BANNER_STATUS_APPROVED == $status) {
            $mBanners->setStatusApproved();
        }

        if($mBanners::BANNER_STATUS_PENDING == $status) {
            $mBanners->setStatusPending();
        }

        if($mBanners::BANNER_STATUS_REJECT == $status) {
            $mBanners->setStatusReject();
        }
        $mBanners->update_status();

        $return = new stdClass();
        $return->success = true;
        $return->errorMessage = "";
        $return->data = array('id' => $banner_id);

        $this->_helper->json($return);

    }

    public function deleteBannerAction($banner_id)
    {

        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->session->userId) {

            $mDocument = new Application_Model_Banners();
            $mDocument->deleteBanner($banner_id);

        }

        $this->_helper->json("success");

    }
}
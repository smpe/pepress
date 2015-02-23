<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

class Support_HelpController extends Smpe_Action
{
    /**
     * Add
     */
    public function Add() {
        $this->response['Title'] = 'Add new help';
        $this->layout();
    }

    /**
     * AddSubmit
     * @return array
     */
    public function AddSubmit() {
        $title = Smpe_InputFilter::string('Title');
        if(empty($title)) {
            return $this->failed('Title cannot be empty.');
        }
        $body = Smpe_InputFilter::string('Body');
        if(empty($body)) {
            return $this->failed('Content cannot be empty.');
        }

        $this->beginTransaction();
        try {
            $help = array(
                'UserID' => 1,
                'CreationTime' => Smpe_Application::$time,
                'UpdateTime' => Smpe_Application::$time,
                'Title' => $title,
            );
            $helpID = Support_Help::data()->insert($help);

            $revision = array(
                'HelpID' => $helpID,
                'UserID' => 1,
                'CreationTime' => Smpe_Application::$time,
                'Status' => '2',
                'StatusTime' => Smpe_Application::$time,
                'Body' => $body,
            );
            Support_HelpRevision::data()->insert($revision);

            $this->commit();
            return $this->succeed($helpID, Smpe_Url::http('Support', 'Help', 'Detail', array($helpID)));
        } catch(Exception $e) {
            $this->rollBack();
            return $this->failed($e->getMessage());
        }
    }

    /**
     * Browse
     */
    public function Browse() {
        $this->pagination = array(
            'Where' => array(
                array('AND', 'a', 'CreationTime', '>=', Smpe_InputFilter::string('CreationTimeMin', INPUT_GET), true),
                array('AND', 'a', 'CreationTime', '<=', Smpe_InputFilter::string('CreationTimeMax', INPUT_GET), true),
                array('AND', 'a', 'Title', 'LIKE', Smpe_InputFilter::string('Title', INPUT_GET), true),
                array('AND', 'b', 'Body', 'LIKE', Smpe_InputFilter::string('Body', INPUT_GET), true),
            ),
            'Group' => '',
            'Order' => Smpe_InputFilter::order('order', INPUT_GET),
            'PageIndex' => Smpe_InputFilter::int('PageIndex', INPUT_GET),
            'PageSize' => 30,
        );
        Support_Help::data()->page($this->pagination, $this->data['HelpList'], $this->data['HelpCount']);

        $this->response['Title'] = 'Browse help';
        $this->layout();
    }

    /**
     * Detail
     * @param int $helpID
     * @return array
     */
    public function Detail($helpID = 0) {
        $this->data['Help'] = Support_Help::data()->rowEx(array('HelpID'=>$helpID));
        $this->data['HelpRevision'] = Support_HelpRevision::data()->row(array('HelpID'=>$helpID));

        $this->response['Title'] = 'View help detail';
        $this->layout();
    }

    /**
     * DeleteSubmit
     */
    public function DeleteSubmit() {
        $helpID = Smpe_InputFilter::intEx('HelpID');

        $this->beginTransaction();
        try {
            $aRow = Support_Help::data()->delete(array('HelpID'=>$helpID));
            Support_HelpRevision::data()->delete(array('HelpID'=>$helpID));

            $this->commit();
            return $this->succeed($aRow);
        } catch(Exception $e) {
            $this->rollBack();
            return $this->failed($e->getMessage());
        }
    }

    /**
     * Edit
     * @param int $helpID
     * @return array
     */
    public function Edit($helpID = 0) {
        $this->data['Help'] = Support_Help::data()->rowEx(array('HelpID'=>$helpID));
        $this->data['HelpRevision'] = Support_HelpRevision::data()->row(array('HelpID'=>$helpID));

        $this->response['Title'] = 'Edit help';
        $this->layout();
    }

    /**
     * EditSubmit
     */
    public function EditSubmit() {
        $helpID = Smpe_InputFilter::intEx('HelpID');
        $helpRevisionID = Smpe_InputFilter::intEx('HelpRevisionID');
        $title = Smpe_InputFilter::stringEx('Title');
        $body = Smpe_InputFilter::stringEx('Body');

        $this->beginTransaction();
        try {
            $help = array(
                'UpdateTime' => Smpe_Application::$time,
                'Title' => $title,
            );
            Support_Help::data()->update($help, array('HelpID'=>$helpID));

            $revision = array(
                'Body' => $body,
            );
            Support_HelpRevision::data()->update($revision, array('HelpRevisionID'=>$helpRevisionID));

            $this->commit();
            return $this->succeed($helpID, Smpe_Url::http('Support', 'Help', 'Detail', array($helpID)));
        } catch(Exception $e) {
            $this->rollBack();
            return $this->failed($e->getMessage());
        }
    }

    /**
     * Index
     */
    public function Index() {
        $this->response['Title'] = 'Help';
        $this->layout();
    }
}

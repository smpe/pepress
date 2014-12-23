<?php
class Support_HelpController extends Smpe_Mvc_Action
{
    /**
     * Add
     */
    public function Add() {
        $this->layout();
    }

    /**
     * AddSubmit
     * @return array
     */
    public function AddSubmit() {
        $title = Smpe_Mvc_Filter::string('Title');
        if(empty($title)) {
            return $this->failed('Title cannot be empty.');
        }
        $body = Smpe_Mvc_Filter::string('Body');
        if(empty($body)) {
            return $this->failed('Content cannot be empty.');
        }

        $time = date('Y-m-d H:i:s');

        $this->beginTransaction();
        try {
            $help = array(
                'UserID' => 1,
                'CreationTime' => $time,
                'UpdateTime' => $time,
                'Title' => $title,
            );

            $helpID = Support_Help::data()->insert($help);

            $revision = array(
                'HelpID' => $helpID,
                'UserID' => 1,
                'CreationTime' => $time,
                'Status' => '2',
                'StatusTime' => $time,
                'Body' => $body,
            );

            Support_HelpRevision::data()->insert($revision);

            $this->commit();
            return $this->succeed($helpID);
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
                array('AND', 'a', 'CreationTime', '>=', Smpe_Mvc_Filter::string('CreationTimeMin', INPUT_GET), true),
                array('AND', 'a', 'CreationTime', '<=', Smpe_Mvc_Filter::string('CreationTimeMax', INPUT_GET), true),
                array('AND', 'a', 'Title', 'LIKE', Smpe_Mvc_Filter::string('Title', INPUT_GET), true),
                array('AND', 'b', 'Body', 'LIKE', Smpe_Mvc_Filter::string('Body', INPUT_GET), true),
            ),
            'Group' => '',
            'Order' => Smpe_Mvc_Filter::order('order', INPUT_GET),
            'PageIndex' => Smpe_Mvc_Filter::int('PageIndex', INPUT_GET),
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
        $this->data['Help'] = Support_Help::data()->row(array('HelpID'=>$helpID));
        if(empty($this->data['Help'])) {
            return $this->failed('Content does not exist.');
        }

        $this->data['HelpRevision'] = Support_HelpRevision::data()->row(array('HelpID'=>$helpID));

        $this->layout();
    }

    /**
     * Edit
     * @param int $helpID
     * @return array
     */
    public function Edit($helpID = 0) {
        $this->data['Help'] = Support_Help::data()->row(array('HelpID'=>$helpID));
        if(empty($this->data['Help'])) {
            return $this->failed('Content does not exist.');
        }

        $this->data['HelpRevision'] = Support_HelpRevision::data()->row(array('HelpID'=>$helpID));

        $this->layout();
    }

    /**
     * EditSubmit
     */
    public function EditSubmit() {
        $helpID = Smpe_Mvc_Filter::int('HelpID');
        if(empty($helpID)) {
            return $this->failed('HelpID cannot be empty.');
        }
        $helpRevisionID = Smpe_Mvc_Filter::int('HelpRevisionID');
        if(empty($helpRevisionID)) {
            return $this->failed('HelpRevisionID cannot be empty.');
        }
        $title = Smpe_Mvc_Filter::string('Title');
        if(empty($title)) {
            return $this->failed('Title cannot be empty.');
        }
        $body = Smpe_Mvc_Filter::string('Body');
        if(empty($body)) {
            return $this->failed('Content cannot be empty.');
        }

        $time = date('Y-m-d H:i:s');

        $this->beginTransaction();
        try {
            $help = array(
                'UpdateTime' => $time,
                'Title' => $title,
            );

            Support_Help::data()->update($help, array('HelpID'=>$helpID));

            $revision = array(
                'Body' => $body,
            );

            Support_HelpRevision::data()->update($revision, array('HelpRevisionID'=>$helpRevisionID));

            $this->commit();
            return $this->succeed($helpID);
        } catch(Exception $e) {
            $this->rollBack();
            return $this->failed($e->getMessage());
        }
    }

    /**
     * Index
     */
    public function Index() {
        $this->layout();
    }
}

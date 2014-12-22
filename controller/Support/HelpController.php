<?php
class Support_HelpController extends Smpe_Mvc_Action
{
    public function Add() {
        $this->layout();
    }

    public function AddSubmit() {
        $title = Smpe_Mvc_Filter::string('title');
        if(empty($title)) {
            return $this->failed('Title cannot be empty.');
        }
        $body = Smpe_Mvc_Filter::string('body');
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

    public function Browse() {
        $this->layout();
    }

    public function Detail($helpID = 0) {
        $this->data['help'] = Support_Help::data()->row(array('HelpID'=>$helpID));
        if(empty($this->data['help'])) {
            return $this->failed('Content does not exist.');
        }

        $this->data['help_revision'] = Support_HelpRevision::data()->row(array('HelpID'=>$helpID));

        $this->layout();
    }

    public function Edit($helpID = 0) {
        $this->data['help'] = Support_Help::data()->row(array('HelpID'=>$helpID));
        if(empty($this->data['help'])) {
            return $this->failed('Content does not exist.');
        }

        $this->data['help_revision'] = Support_HelpRevision::data()->row(array('HelpID'=>$helpID));

        $this->layout();
    }

    public function EditSubmit() {
    }
}

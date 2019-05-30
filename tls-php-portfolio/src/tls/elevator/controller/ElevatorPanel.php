<?php
/*
 * Copyright ©2019. Thomas L. Shuford Jr. All Rights Reserved.
 * 
 * PERMISSION
 * Permission to use, copy, modify, and redistribute this software for not-for-profit purposes is hereby granted, provided that the 
 * following conditions are met:
 * 
 *  1) Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 *  2) Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer
 *     in the documentation and/or other materials provided with the distribution.
 *  3) The name of Thomas L. Shuford Jr. may not be used to endorse or promote products 
 *     derived from this software without specific prior written permission.
 * 
 * DISCLAIMER
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER “AS IS” AND ANY EXPRESS OR IMPLIED WARRANTIES, 
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE 
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR 
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE 
 * USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 */

namespace tls\elevator\controller;
use tls\elevator\Constant;

class ElevatorPanel extends Panel
{

    private $copyright =
            "* Copyright ©2019. Thomas L. Shuford Jr. All Rights Reserved.\n"
            . "* \n"
            . "* PERMISSION\n"
            . "* Permission to use, copy, modify, and redistribute this software for not-for-profit purposes is hereby granted, provided that the\n"
            . "* following conditions are met:\n"
            . "* \n"
            . "*  1) Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.\n"
            . "*  2) Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer\n"
            . "*     in the documentation and/or other materials provided with the distribution.\n"
            . "*  3) The name of Thomas L. Shuford Jr. may not be used to endorse or promote products\n"
            . "*     derived from this software without specific prior written permission.\n"
            . "* \n" 
            . "* DISCLAIMER\n"
            . "* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER “AS IS” AND ANY EXPRESS OR IMPLIED WARRANTIES, \n" 
            . "* INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE\n" 
            . "* DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,\n" 
            . "* SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR\n" 
            . "* SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, \n"
            . "* WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE\n" 
            . "* USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.\n";
    
    private $elevatorFloorButtons; // array of class ElevatorFloorButton
    private $doorOpenButton; // class  ElevatorDoorButton
    private $doorCloseButton; // class ElevatorDoorButton
    private $elevatorId = 0; // int
    private $numberOfFloors = 1; // int

    // public function __construct()
    // {
    //     // ep = elevator passenger panel, ef = elevator freight panel.
    //     // Normally, I'd make these values constants in a static class of constants or maybe in a config file
    //     this(Constant.ELEV_PANEL_TYPE_PASSENGER, 1, 6);
    // }
    
    public function __construct(string $type = Constant::ELEV_PANEL_TYPE_PASSENGER, int $elevatorId = 1, int $numberFloors = 6)
    {
        $this->panelType = $type;
        $this->elevatorId = $elevatorId;
        $this->numberOfFloors = $numberFloors;
        $this->initElevatorPanel();
    }
    
    private function initElevatorPanel(): void
    {
        $this->elevatorFloorButtons = array (); // array of ElevatorFloorButton
        
        for ($i = 0; $i < $this->numberOfFloors; $i++)
        {
            $this->elevatorFloorButtons[$i] = new ElevatorFloorButton($this->elevatorId, $i+1);
        }
        $this->doorOpenButton = new ElevatorDoorButton(Constant::ELEV_PANEL_DOOR_BTN_TYPE_OPEN, $this->elevatorId);
        $this->doorCloseButton = new ElevatorDoorButton(Constant::ELEV_PANEL_DOOR_BTN_TYPE_CLOSE, $this->elevatorId);
    }
    public function getElevatorFloorButtons(): ?array // returns an array of ElevatorFloorButton
    {
        return $this->elevatorFloorButtons;
    }

    public function setElevatorFloorButtons(array $elevatorFloorButtons): void // input is array of ElevatorFloorButton
    {
        $this->elevatorFloorButtons = $elevatorFloorButtons;
    }

    public function getDoorOpenButton(): ?ElevatorDoorButton
    {
        return $this->doorOpenButton;
    }

    public function setDoorOpenButton(ElevatorDoorButton $doorOpenButton): void
    {
        $this->doorOpenButton = $doorOpenButton;
    }

    public function getDoorCloseButton(): ?ElevatorDoorButton
    {
        return $this->doorCloseButton;
    }

    public function setDoorCloseButton(ElevatorDoorButton $doorCloseButton): void
    {
        $this->doorCloseButton = $doorCloseButton;
    }

    public function getElevatorId(): int
    {
        return $this->elevatorId;
    }

    public function setElevatorId(int $elevatorId): void
    {
        $this->elevatorId = $elevatorId;
    }

    public function getNumberOfFloors(): int
    {
        return $this->numberOfFloors;
    }

    public function setNumberOfFloors(int $numberOfFloors): void
    {
        $this->numberOfFloors = $numberOfFloors;
    }

    public static function main(...$args): void
    {
        // TODO Auto-generated method stub

    }

}
?>

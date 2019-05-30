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

class FloorButton extends Button //implements Comparable<FloorButton>
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
    
    private $floorNumber = 0; //int
    // elapsedTimeButtonOn is in seconds. This gets set to 0 when buttonOn is set to false because elevator stopped at floor and is going
    // in correct direction for button.
    // Gets updated by buildingElevatorController.controller
    private $elapsedTimeButtonOn = 0; //int
    private $numberDispatchedElevator = 0; // int - If equal to 0 then no elevator has been dispatched. Once elevator gets to floor, it will be reset to 0
    private $panelType = Constant::FLOOR_PANEL_TYPE_PASSENGER; // string
    
    // public function __construct()
    // {
    //     this(Constant.FLOOR_PANEL_BTN_TYPE_UP, Constant.FLOOR_PANEL_TYPE_PASSENGER, 1); // u = up button, dw = down button
    // }
    
    public function __construct (string $type = Constant::FLOOR_PANEL_BTN_TYPE_UP, string $panelType = Constant::FLOOR_PANEL_TYPE_PASSENGER, int $floorNumber = 1)
    {
        $this->buttonType = $type;
        $this->panelType = $panelType;
        $this->floorNumber = $floorNumber;
    }

    public function getFloorNumber(): int
    {
        return $this->floorNumber;
    }

    public function setFloorNumber(int $floorNumber): void
    {
        $this->floorNumber = $floorNumber;
    }

    public function getElapsedTimeButtonOn(): int
    {
        return $this->elapsedTimeButtonOn;
    }

    public function setElapsedTimeButtonOn(int $elapsedTimeButtonOn): void
    {
        $this->elapsedTimeButtonOn = $elapsedTimeButtonOn;
    }

    public function getNumberDispatchedElevator(): int
    {
        return $this->numberDispatchedElevator;
    }

    public function setNumberDispatchedElevator(int $numberDispatchedElevator): void
    {
        $this->numberDispatchedElevator = $numberDispatchedElevator;
    }

    public function getPanelType(): ?string
    {
        return $this->panelType;
    }

    public function setPanelType(string $panelType): void
    {
        $this->panelType = $panelType;
    }

    public static function compareElapsedTimeButtonOn(FloorButton $compareFloorButton1, FloorButton $compareFloorButton2): int
    {
        if ($compareFloorButton1->getElapsedTimeButtonOn() == $compareFloorButton2->getElapsedTimeButtonOn())
        {
            return 0;
        }
        
        return ($compareFloorButton1->getElapsedTimeButtonOn() > $compareFloorButton2->getElapsedTimeButtonOn())? -1 : 1;
    }
    
    public static function main(string ...$args): void
    {
        // TODO Auto-generated method stub

    }

}
?>

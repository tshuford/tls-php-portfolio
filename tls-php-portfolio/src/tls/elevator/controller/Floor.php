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

class Floor
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
    
    private $floorNumber = 0; // int
    private $passengerPanel; // class FloorPanel
    private $freightPanel; // class FloorPanel
    private $numberOfFloors = 0; // int

    // public function __construct()
    // {
        
    // }
    
    public function __construct (int $floorNumber, int $numberOfFloors)
    {
        $this->floorNumber = $floorNumber;
        $this->numberOfFloors = $numberOfFloors;
        $this->passengerPanel = new FloorPanel(Constant::FLOOR_PANEL_TYPE_PASSENGER, $this->floorNumber, $this->numberOfFloors); //fp = floor passenger panel
        $this->freightPanel = new FloorPanel(Constant::FLOOR_PANEL_TYPE_FREIGHT, $this->floorNumber, $this->numberOfFloors); // ff = floor freight panel
        
    }
    
    public function getFloorNumber(): int
    {
        return $this->floorNumber;
    }

    public function setFloorNumber(int $floorNumber): void
    {
        $this->floorNumber = $floorNumber;
    }

    public function getPassengerPanel(): ?FloorPanel
    {
        return $this->passengerPanel;
    }

    public function setPassengerPanel(FloorPanel $passengerPanel): void
    {
        $this->passengerPanel = $passengerPanel;
    }

    public function getFreightPanel(): ?FloorPanel
    {
        return $this->freightPanel;
    }

    public function setFreightPanel(FloorPanel $freightPanel): void
    {
        $this->freightPanel = $freightPanel;
    }

    public function getNumberOfFloors(): int
    {
        return $this->numberOfFloors;
    }

    public function setNumberOfFloors(int $numberOfFloors): void
    {
        $this->numberOfFloors = $numberOfFloors;
    }

    public static function main(string ...$args): void
    {
        // TODO Auto-generated method stub

    }

}
?>

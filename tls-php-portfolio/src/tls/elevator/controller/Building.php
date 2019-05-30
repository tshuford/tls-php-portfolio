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

class Building
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
    
    private $numberOfElevators = 1; //  int
    private $numberOfFloors = 1; //  int
    private $elevators = array(); // array of  Elevator
    private $floors = array(); // array of Floor
    private $elevatorTypes; // array of string
    private $elevatorController; //  BuildingElevatorController object
    private $controllerRunInterval = 1; // int - in seconds
    
    public function __construct (int $numberOfElevators, array $elevatorTypes, int $numberOfFloors, int $controllerRunInterval)
    {
        $this->numberOfElevators = $numberOfElevators;
        $this->numberOfFloors = $numberOfFloors;
        $this->elevatorTypes = $elevatorTypes;
        $this->controllerRunInterval = $controllerRunInterval;
        
        //TODO check if elevatorTypes.length is equal to numberOfElevators. If not, then return.
        
        $this->elevators = array(); // array of Elevator [numberOfElevators]
        $this->floors = array(); // array of Floor [numberOfFloors]
        
        for($i=0; $i < $this->numberOfElevators; $i++)
        {
            $this->elevators[$i] = new Elevator($this->elevatorTypes[$i], $i+1, $this->numberOfFloors);
        }
        
        for($i=0; $i < $this->numberOfFloors; $i++)
        {
            $this->floors[$i] = new Floor($i+1, $this->numberOfFloors);
        }
        
        $this->elevatorController = new BuildingElevatorController($this->elevators, $this->numberOfElevators, $this->floors, $this->numberOfFloors, $this->controllerRunInterval);
    }
    
    public function getNumberOfElevators(): int
    {
        return $this->numberOfElevators;
    }

    public function setNumberOfElevators(int $numberOfElevators): void
    {
        $this->numberOfElevators = $numberOfElevators;
    }

    public function getNumberOfFloors(): int
    {
        return $this->numberOfFloors;
    }

    public function setNumberOfFloors(int $numberOfFloors): void
    {
        $this->numberOfFloors = $numberOfFloors;
    }

    public function getElevators(): ?array // array of Elevator
    {
        return $this->elevators;
    }

    public function setElevators($elevators): void //input  array of Elevator
    {
        $this->elevators = $elevators;
    }

    public function getFloors(): ?array // array of  Floor
    {
        return $this->floors;
    }

    public function setFloors(array $floors): void // input array of Floor
    {
        $this->floors = $floors;
    }

    public function getElevatorTypes(): ?array // array of string
    {
        return $this->elevatorTypes;
    }

    public function setElevatorTypes($elevatorTypes):  void // input array of string 
    {
        $this->elevatorTypes = $elevatorTypes;
    }

    public function getElevatorController(): ?BuildingElevatorController
    {
        return $this->elevatorController;
    }

    public function setElevatorController(BuildingElevatorController $elevatorController): void
    {
        $this->elevatorController = $elevatorController;
    }

    public function getControllerRunInterval(): int
    {
        return $this->controllerRunInterval;
    }

    public function setControllerRunInterval(int $controllerRunInterval): void
    {
        $this->controllerRunInterval = $controllerRunInterval;
    }

    public static function main(array $args = null): void
    {
        $numberOfElevators = 4; // int
        $numberOfFloors = 6; // int
        $controllerRunInterval = 2; // int - seconds
        $elevatorTypes = array(Constant::ELEV_TYPE_PASSENGER,Constant::ELEV_TYPE_PASSENGER,Constant::ELEV_TYPE_FREIGHT,Constant::ELEV_TYPE_FREIGHT); // array of string
        $building = new Building( $numberOfElevators, $elevatorTypes, $numberOfFloors, $controllerRunInterval); // Building object
        
        $building->elevatorController->controller();
        

    }

}
?>

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

class Elevator
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
    
    private $inService = true; // bool
    // elevatorMalfunction is set by the building elevator controller if it detects a serious malfunction. If elevatorMalfunction is set to true,
    // then inService is set to false.
    private $elevatorMalfunction = false; //bool
    private $restrictedToFreight = false; // bool - only used by building elevator controller if freightElevatorPanel is not null
    private $weightSensor = null; // WeightSensor
    private $isUnderWeightLimit = true; // bool
    private $currentFloor = 1; // int - Initially all elevators will start on first floor
    private $currentDirection = Constant::ELEV_CURRENT_DIRECTION_IDLE; // int - down = -1, parked(idle no direction) = 0, up = 1
    // could have current direction, but be stopped at floor. If currentDirection is 0 (parked/idle) then inMotion will also be false
    // When currentDirection is set to idle or a different direction by the building elevator controller, the controller will also reset all panel elevator
    // floor buttons to off for the elevator.
    private $inMotion = false; // bool
    // To determine if the elevator needs to stop at a floor; pickupFloors, passengerElevatorPanel, and freightElevatorPanel (if not null) need to
    // be queried.
    // pickupFloors is set by the building elevator controller based on the floor panel buttons on the floors and elevator direction and maybe on the fly
    // calculated distance to the floor (For example: closest elevator going in the correct direction and not past the floor gets the pickup).
    private $pickupFloors; // array of PickupFloor
    private $pickupOnly = false; // bool
    private $pickupOnlyDirection = Constant::ELEV_PICKUPONLY_DIRECTION_IDLE; // int - Idle means that the elevator is already at floor for pickupOnly
    // freight elevator will have both a passenger and freight elevator panel. Passenger elevator will only have the passenger panel.
    // freight panel will allow the building elevator controller to know which floors to open the back freight door on.
    private $passengerElevatorPanel = null; // ElevatorPanel
    private $freightElevatorPanel = null; // ElevatorPanel - if null after init, then not a freight elevator.
    // I'm assuming that the floor door is not a door (i.e. always closed if no elevator behind door) unless the elevator is stopped on the floor.
    // So, the state of the floor door becomes the state of the elevator door that has stopped on the floor and the floor door opens and then closes
    // with the elevator door.
    private $passengerDoor = null; // ElevatorDoor
    private $freightDoor = null; // ElevatorDoor - if null after init, then not a freight elevator
    // default p is passenger, f is freight. Normally I'd have the default in a class of constants or config file
    private $elevatorType = Constant::ELEV_TYPE_PASSENGER; // string 
    private $numberOfFloors = 0; // int
    private $elevatorId = 0; // int

    // public function __construct__ ()
    // {
    //     this(Constant::ELEV_TYPE_PASSENGER, 1, 6); // 6 = default number of floors. Normally, I'd have set these values up in a static class of constants or config file
    // }

    public function __construct(string $type = Constant::ELEV_TYPE_PASSENGER, int $elevatorId = 1, int $numberFloors = 6)
    {
        $this->elevatorType = $type;
        $this->elevatorId = $elevatorId;
        $this->numberOfFloors = $numberFloors;
        $this->initElevator(); 
    }
    
    private function initElevator (): void
    {
        // initialize the elevator objects

        $this->passengerElevatorPanel = new ElevatorPanel(Constant::ELEV_TYPE_PASSENGER, $this->elevatorId, $this->numberOfFloors);
        $this->passengerDoor = new ElevatorDoor(Constant::ELEV_TYPE_PASSENGER, $this->elevatorId);
        
        if ($this->elevatorType == Constant::ELEV_TYPE_FREIGHT)
        {
            $this->weightSensor = new WeightSensor(Constant::ELEV_FREIGHT_MAX_WEIGHT, $this->elevatorId);
            // I'm assuming that every floor has a freight door for the freight elevator
            $this->freightElevatorPanel = new ElevatorPanel(Constant::ELEV_TYPE_FREIGHT, $this->elevatorId, $this->numberOfFloors);
            $this->freightDoor = new ElevatorDoor(Constant::ELEV_TYPE_FREIGHT, $this->elevatorId);
        }
        else if ($this->elevatorType == Constant::ELEV_TYPE_PASSENGER)
        {
            $this->weightSensor = new WeightSensor(Constant::ELEV_PASSENGER_MAX_WEIGHT, $this->elevatorId);
        }
        
        $this->pickupFloors = array(); // array of PickupFloor
        
        for($i=0; $i < $this->numberOfFloors; $i++)
        {
            $this->pickupFloors[$i] = new PickupFloor($i+1);
        }
        return;
    }

    public function isInService(): bool
    {
        return $this->inService;
    }
    public function setInService(bool $inService): void
    {
        $this->inService = $inService;
    }
    public function isElevatorMalfunction(): bool
    {
        return $this->elevatorMalfunction;
    }
    public function setElevatorMalfunction(bool $elevatorMalfunction): void
    {
        $this->elevatorMalfunction = $elevatorMalfunction;
    }
    public function isRestrictedToFreight(): bool
    {
        return $this->restrictedToFreight;
    }
    public function setRestrictedToFreight(bool $restrictedToFreight): void
    {
        $this->restrictedToFreight = $restrictedToFreight;
    }
    public function getWeightSensor(): ?WeightSensor
    {
        return $this->weightSensor;
    }
    public function setWeightSensor(WeightSensor $weightSensor = null): void
    {
        $this->weightSensor = $weightSensor;
    }
    public function isUnderWeightLimit(): bool
    {
        return $this->isUnderWeightLimit;
    }
    public function setUnderWeightLimit(bool $isUnderWeightLimit): void
    {
        $this->isUnderWeightLimit = $isUnderWeightLimit;
    }
    public function getCurrentFloor(): int
    {
        return $this->currentFloor;
    }
    public function setCurrentFloor(int $currentFloor): void
    {
        $this->currentFloor = $currentFloor;
    }
    public function getCurrentDirection(): int
    {
        return $this->currentDirection;
    }
    public function setCurrentDirection(int $currentDirection): void
    {
        $this->currentDirection = $currentDirection;
    }
    public function isInMotion(): bool
    {
        return $this->inMotion;
    }
    public function setInMotion(bool $inMotion): void
    {
        $this->inMotion = $inMotion;
    }
    public function getPickupFloors(): ?array
    {
        return $this->pickupFloors;
    }
    public function setPickupFloors(array $pickupFloors = null): void // array of PickupFloors
    {
        $this->pickupFloors = $pickupFloors;
    }
    public function isPickupOnly(): bool
    {
        return $this->pickupOnly;
    }
    public function setPickupOnly(bool $pickupOnly): void
    {
        $this->pickupOnly = $pickupOnly;
    }
    public function getPickupOnlyDirection(): int
    {
        return $this->pickupOnlyDirection;
    }
    public function setPickupOnlyDirection(int $pickupOnlyDirection): void
    {
        $this->pickupOnlyDirection = $pickupOnlyDirection;
    }
    public function getPassengerElevatorPanel(): ?ElevatorPanel
    {
        return $this->passengerElevatorPanel;
    }
    public function setPassengerElevatorPanel(ElevatorPanel $passengerElevatorPanel = null): void
    {
        $this->passengerElevatorPanel = $passengerElevatorPanel;
    }
    public function getFreightElevatorPanel(): ?ElevatorPanel
    {
        return $this->freightElevatorPanel;
    }
    public function setFreightElevatorPanel(ElevatorPanel $freightElevatorPanel = null): void
    {
        $this->freightElevatorPanel = $freightElevatorPanel;
    }
    public function getPassengerDoor(): ?ElevatorDoor
    {
        return $this->passengerDoor;
    }
    public function setPassengerDoor(ElevatorDoor $passengerDoor = null): void
    {
        $this->passengerDoor = $passengerDoor;
    }
    public function getFreightDoor(): ?ElevatorDoor
    {
        return $this->freightDoor;
    }
    public function setFreightDoor(ElevatorDoor $freightDoor = null): void
    {
        $this->freightDoor = $freightDoor;
    }
    public function getElevatorType(): ?string
    {
        return $this->elevatorType;
    }
    public function setElevatorType(string $elevatorType = null): void
    {
        $this->elevatorType = $elevatorType;
    }
    public function getNumberOfFloors(): int
    {
        return $this->numberOfFloors;
    }
    public function setNumberOfFloors(int $numberOfFloors): void
    {
        $this->numberOfFloors = $numberOfFloors;
    }
    public function getElevatorId(): int
    {
        return $this->elevatorId;
    }
    public function setElevatorId(int $elevatorId): void
    {
        $this->elevatorId = $elevatorId;
    }
    public static function main(string ...$args): void
    {
        // TODO Auto-generated method stub

    }
}
?>

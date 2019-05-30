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

// package tls.elevator;

// import java.util.ArrayList;
// import java.util.Collections;

namespace tls\elevator\controller;
use tls\elevator\Constant;

class BuildingElevatorController
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
    
    private $numberOfElevators = 1; // int
    private $numberOfFloors = 1; // int
    private $elevators; //  array of Elevator
    private $floors; // array of Floor
    private $controllerRunInterval = 1; // int - in seconds
    private $controllerRunning = true; // bool - If set to true controller will run. If set to false, controller will stop if it is currently running.
    private $loopTimeDuration = 60; //  int - each loop in the controller method in seconds
    
    public function __construct (array $elevators, int $numberOfElevators, array $floors, int $numberOfFloors, int $controllerRunInterval)
    {
        $this->elevators = $elevators;
        $this->numberOfElevators = $numberOfElevators;
        $this->floors = $floors;
        $this->numberOfFloors = $numberOfFloors;
        $this->controllerRunInterval = $controllerRunInterval;
    }
    
    private function setupTestState(): void
    {
        //Setup floors
        
        //floor 1
        $this->floors[0]->getPassengerPanel()->getUpButton()->setButtonOn(true);
        $this->floors[0]->getPassengerPanel()->getUpButton()->setElapsedTimeButtonOn(180);
        
        //floor 2
        $this->floors[1]->getPassengerPanel()->getDownButton()->setButtonOn(true);
        $this->floors[1]->getPassengerPanel()->getDownButton()->setElapsedTimeButtonOn(10);
        
        // Freight pickup will not get dispatched this time because least wait time and the the idle freight elevator is used for
        // floor 3 freight pickup with a longer wait time. The other freight elevator is going in the opposite direction
        $this->floors[1]->getFreightPanel()->getDownButton()->setButtonOn(true);
        $this->floors[1]->getFreightPanel()->getDownButton()->setElapsedTimeButtonOn(10);
        
        //floor 3
        $this->floors[2]->getPassengerPanel()->getDownButton()->setButtonOn(true);
        $this->floors[2]->getPassengerPanel()->getDownButton()->setNumberDispatchedElevator(2); //passenger only elevator
        $this->floors[2]->getPassengerPanel()->getDownButton()->setElapsedTimeButtonOn(90);
        
        $this->floors[2]->getFreightPanel()->getUpButton()->setButtonOn(true);
        $this->floors[2]->getFreightPanel()->getUpButton()->setElapsedTimeButtonOn(100);
        
        //floor 4
        
        //floor 5
        
        $this->floors[4]->getPassengerPanel()->getUpButton()->setButtonOn(true);
        $this->floors[4]->getPassengerPanel()->getUpButton()->setElapsedTimeButtonOn(90);
        
        $this->floors[4]->getFreightPanel()->getUpButton()->setButtonOn(true);
        $this->floors[4]->getFreightPanel()->getUpButton()->setElapsedTimeButtonOn(80);
        
        //floor 6
        
        
        //Elevator data
        
        //Elevator 1 passenger only
        $this->elevators[0]->setCurrentDirection(Constant::ELEV_CURRENT_DIRECTION_IDLE);
        $this->elevators[0]->setCurrentFloor(1);
        
        //Elevator 2 passenger only
        $this->elevators[1]->setCurrentDirection(Constant::ELEV_CURRENT_DIRECTION_DOWN);
        $this->elevators[1]->setCurrentFloor(4);
        $this->elevators[1]->setInMotion(true);
        $this->elevators[1]->getPassengerElevatorPanel()->getElevatorFloorButtons()[2]->setButtonOn(true); // passenger floor 3 button
        $this->elevators[1]->getPassengerElevatorPanel()->getElevatorFloorButtons()[0]->setButtonOn(true); // passenger floor 1 button
        
        //Elevator 3 freight and passenger
        $this->elevators[2]->setCurrentDirection(Constant::ELEV_CURRENT_DIRECTION_UP);
        $this->elevators[2]->setCurrentFloor(5);
        $this->elevators[2]->setInMotion(false);
        $this->elevators[2]->getPassengerElevatorPanel()->getElevatorFloorButtons()[5]->setButtonOn(true); // passenger floor 6 button
        $this->elevators[2]->getFreightElevatorPanel()->getElevatorFloorButtons()[5]->setButtonOn(true); // Freight floor 6 button
        
        //Elevator 4 freight and passenger
        
        $this->elevators[3]->setCurrentDirection(Constant::ELEV_CURRENT_DIRECTION_IDLE);
        $this->elevators[3]->setCurrentFloor(6);
        
    }
    
    private function findFloorsNeedingDispatch(): array // of class FloorButton (ArrayList)
    {
        $floorButtons = array(); //array of class FloorButton
        
        for($i = 0; $i < $this->numberOfFloors; $i++)
        {
            
            if ($this->floors[$i]->getPassengerPanel()->getUpButton() != null
                    && $this->floors[$i]->getPassengerPanel()->getUpButton()->isButtonOn()
                    && $this->floors[$i]->getPassengerPanel()->getUpButton()->getNumberDispatchedElevator() == 0)
            {
                $floorButtons[] = $this->floors[$i]->getPassengerPanel()->getUpButton();
            }
            
            if ($this->floors[$i]->getPassengerPanel()->getDownButton() != null
                    && $this->floors[$i]->getPassengerPanel()->getDownButton()->isButtonOn()
                    && $this->floors[$i]->getPassengerPanel()->getDownButton()->getNumberDispatchedElevator() == 0)
            {
                $floorButtons[] = $this->floors[$i]->getPassengerPanel()->getDownButton();
            }
            
            if ($this->floors[$i]->getFreightPanel()->getUpButton() != null
                    && $this->floors[$i]->getFreightPanel()->getUpButton()->isButtonOn()
                    && $this->floors[$i]->getFreightPanel()->getUpButton()->getNumberDispatchedElevator() == 0)
            {
                $floorButtons[] = $this->floors[$i]->getFreightPanel()->getUpButton();
            }
            
            if ($this->floors[$i]->getFreightPanel()->getDownButton() != null
                    && $this->floors[$i]->getFreightPanel()->getDownButton()->isButtonOn()
                    && $this->floors[$i]->getFreightPanel()->getDownButton()->getNumberDispatchedElevator() == 0)
            {
                $floorButtons[] = $this->floors[$i]->getFreightPanel()->getDownButton();
            }
        }
        
        // sort floorButtons by descending order of elapsedTimeButtonOn

        $usortSuccess = usort($floorButtons, 'tls\elevator\controller\FloorButton::compareElapsedTimeButtonOn');

        return $floorButtons;
    }
    
    private function evalElevator(FloorButton $button = null, Elevator $elevator = null, Elevator $dispatchedElevator = null):  ?Elevator
    {
        $rtnElevator = $dispatchedElevator; // Elevator
        $elevatorCanDispatch = false; // bool
        
        if ($button != null && $elevator != null)
        {
            $buttonFloor = $button->getFloorNumber(); // int
            $buttonType = $button->getButtonType(); // String 
            $elevatorFloor = $elevator->getCurrentFloor(); // int 
            $elevatorDirection = $elevator->getCurrentDirection(); // int 
            $floorDistance = abs($buttonFloor - $elevatorFloor); // int 
            $pickupOnly = $elevator->isPickupOnly(); // bool
            
            if (strcasecmp($buttonType, Constant::FLOOR_PANEL_BTN_TYPE_UP)== 0)
            {
                if ($elevatorDirection == Constant::ELEV_CURRENT_DIRECTION_UP && !$pickupOnly)
                {
                    if ($elevatorFloor <= $buttonFloor)
                    {
                        $elevatorCanDispatch = true;
                    }
                }
                else if ($elevatorDirection == Constant::ELEV_CURRENT_DIRECTION_IDLE && !$pickupOnly)
                {
                    $elevatorCanDispatch = true;
                }
            }
            else if (strcasecmp($buttonType,Constant::FLOOR_PANEL_BTN_TYPE_DOWN) == 0)
            {
                if ($elevatorDirection == Constant::ELEV_CURRENT_DIRECTION_DOWN && !$pickupOnly)
                {
                    if ($elevatorFloor >= $buttonFloor)
                    {
                        $elevatorCanDispatch = true;
                    }
                }
                else if ($elevatorDirection == Constant::ELEV_CURRENT_DIRECTION_IDLE && !$pickupOnly)
                {
                    $elevatorCanDispatch = true;
                }
            }
            
            if ($elevatorCanDispatch)
            {
                if ($rtnElevator != null)
                {
                    // return the elevator that is closest to the floor.
                    $rtnElevatorFloorDistance = abs($buttonFloor - $rtnElevator->getCurrentFloor()); // int 
                    if ($floorDistance < $rtnElevatorFloorDistance)
                    {
                        $rtnElevator = $elevator;
                    }
                }
                else
                {
                    $rtnElevator = $elevator;
                }
            }
            
        }
        
        
        return $rtnElevator;
    }
    
    private function dispatchElevators(array $floorButtons):  void // input param is array of class floorButton 
    {
        // TODO floorButtons is sorted by descending order of elapsedTimeButtonOn
        
        foreach ($floorButtons as $currentButton) // currentButton is instance of FloorButton
        {
            $dispatchedElevator = null; // will be instance of Elevator
            $buttonFloor = null; // int
            $buttonType = null; //string
            $buttonPanelType = null; // string
            $panelType = null;// string
            $elapsedWaitTime = $currentButton->getElapsedTimeButtonOn(); // int
            $pickupType = "";
            $going ="";

            foreach ($this->elevators as $elevator) // elevator is instance of Elevator
            {
                if (strcasecmp($currentButton->getPanelType(), Constant::FLOOR_PANEL_TYPE_FREIGHT) == 0 && strcasecmp($elevator->getElevatorType(), Constant::ELEV_TYPE_FREIGHT) == 0)
                {
                    $dispatchedElevator = $this->evalElevator($currentButton, $elevator, $dispatchedElevator);
                }
                else if (strcasecmp($currentButton->getPanelType(), Constant::FLOOR_PANEL_TYPE_PASSENGER) == 0)
                {
                    $dispatchedElevator = $this->evalElevator($currentButton, $elevator, $dispatchedElevator);
                }
            }
            
            // if dispatchedElevator is not null, then dispatch it and update elevator and currentButton
            $buttonFloor = $currentButton->getFloorNumber(); // int 
            $buttonType = $currentButton->getButtonType(); // string 
            $buttonPanelType = $currentButton->getPanelType(); // string
            $panelType = $currentButton->getPanelType(); // string
            $elapsedWaitTime = $currentButton->getElapsedTimeButtonOn(); // int
            $pickupType = ""; // string
            $going =""; // string
            
            if (strcasecmp($buttonPanelType, Constant::FLOOR_PANEL_TYPE_PASSENGER) == 0)
            {
                $pickupType = "Passenger";
            }
            else
            {
                $pickupType = "Freight";
            }
            
            if (strcasecmp($buttonType, Constant::FLOOR_PANEL_BTN_TYPE_UP) == 0)
            {
                $going = "up";
            }
            else if (strcasecmp($buttonType, Constant::FLOOR_PANEL_BTN_TYPE_DOWN) == 0)
            {
                $going = "down";
            }
            
            $elevatorFloor = null; // int
            $elevatorDirection = null; // int
            $pickupFloor = null; // array of PickupFloor
            $elevatorId = null; // int
            $floorDistance = null; // int
            $directionToFloor = null; // int

            if ($dispatchedElevator != null)
            {
                $elevatorFloor = $dispatchedElevator->getCurrentFloor(); // int
                $elevatorDirection = $dispatchedElevator->getCurrentDirection(); // int
                $pickupFloor = $dispatchedElevator->getPickupFloors(); // array of PickupFloor
                $elevatorId = $dispatchedElevator->getElevatorId(); // int
                $floorDistance = abs($buttonFloor - $elevatorFloor); // int
                $directionToFloor = $buttonFloor - $elevatorFloor; // int
                
                $currentButton->setNumberDispatchedElevator($dispatchedElevator->getElevatorId());
                if ($dispatchedElevator->getCurrentDirection() == Constant::ELEV_CURRENT_DIRECTION_IDLE)
                {
                    if (strcasecmp($buttonType, Constant::FLOOR_PANEL_BTN_TYPE_UP) == 0)
                    {
                        $dispatchedElevator->setCurrentDirection(Constant::ELEV_CURRENT_DIRECTION_UP);
                    }
                    else if (strcasecmp($buttonType, Constant::FLOOR_PANEL_BTN_TYPE_DOWN) == 0)
                    {
                        $dispatchedElevator->setCurrentDirection(Constant::ELEV_CURRENT_DIRECTION_DOWN);
                    }
                    if ($buttonFloor != $elevatorFloor)
                    {
                        $dispatchedElevator->setPickupOnly(true);
                        if ($directionToFloor > 0)
                        {
                            $dispatchedElevator->setPickupOnlyDirection(Constant::ELEV_PICKUPONLY_DIRECTION_UP);
                        }
                        else if ($directionToFloor < 0)
                        {
                            $dispatchedElevator->setPickupOnlyDirection(Constant::ELEV_PICKUPONLY_DIRECTION_DOWN);
                        }
                    }
                }
                
                if (strcasecmp($buttonPanelType, Constant::FLOOR_PANEL_TYPE_PASSENGER) == 0)
                {
                    $pickupFloor[$buttonFloor -1]->setPassengerPickup(true);
                }
                else
                {
                    $pickupFloor[$buttonFloor -1]->setFreightPickup(true);
                }
                //TODO print dispatch info to console
                
                if ($dispatchedElevator->isPickupOnly())
                {
                   echo "<p>Dispatched Elevator " . $elevatorId . " to Floor " . $buttonFloor . " for " . $pickupType . " Pickup Only " 
                           . "going " . $going . " - Elapsed Wait time of " . $elapsedWaitTime . " seconds</p>";
                }
                else
                {
                    echo "<p>Dispatched Elevator " . $elevatorId . " to Floor " . $buttonFloor . " for " . $pickupType . " Pickup " 
                            . "going " . $going . " - Elapsed Wait time of " . $elapsedWaitTime . " seconds</p>";
                }
            }
            else
            {
                // add loop time duration to button's elapsed time duration since it did not get dispatched

                $currentButton->setElapsedTimeButtonOn($currentButton->getElapsedTimeButtonOn() + $this->loopTimeDuration);

                echo "<p>No dispatchable Elevator for Floor " . $buttonFloor . " for " . $pickupType . " pickup "
                         . "going " . $going . " - Elapsed Wait time of " . $elapsedWaitTime . " seconds</p>";
            }
            
        }
        
    }
    
    private function dispatch(): void
    {
        $floorButtons = null; // array of FloorButton
        
        // find floors needing to be dispatched to
        $floorButtons = $this->findFloorsNeedingDispatch();
        
        // find and dispatch elevators for found floors buttons
        $this->dispatchElevators($floorButtons);
        
        // scan each floor for non dispatched button
    }
    
    private function movePickupOnly(Elevator $elevator = null): void
    {
        $elevatorId = $elevator->getElevatorId(); // int 
        $elevatorCurFloor = $elevator->getCurrentFloor(); // int 
        $elevatorCurDirectn = $elevator->getCurrentDirection(); // int 
        $elevatorPickupOnlyDirectn = $elevator->getPickupOnlyDirection(); // int 
        $pickupFloors = null; // array of PickupFloor
        $pickupFloorDest = 0; // int 
        $isPassengerPickup = false; // bool
        $isFreightPickup = false; // bool
        
        $pickupFloors = $elevator->getPickupFloors();
        foreach ($pickupFloors as $pickupFloor ) // pickupFloor is a PickupFloor 
        {
            if ($pickupFloor->isPassengerPickup() || $pickupFloor->isFreightPickup())
            {
                $pickupFloorDest = $pickupFloor->getFloorNumber();
                if ($pickupFloor->isPassengerPickup())
                {
                    $isPassengerPickup = true;
                }
                else if ($pickupFloor->isFreightPickup())
                {
                    $isFreightPickup = true;
                }
                break;
            }
        }
        if ($pickupFloorDest != 0)
        {
            if ($pickupFloorDest == $elevator->getCurrentFloor())
            {
                $elevator->setPickupOnly(false);
                $elevator->setInMotion(false);
            }
            else if (strcasecmp($elevatorPickupOnlyDirectn, Constant::ELEV_PICKUPONLY_DIRECTION_UP) == 0)
            {
                $elevator->setInMotion(true);
                $elevator->setCurrentFloor($elevatorCurFloor + 1);
            }
            else if (strcasecmp($elevatorPickupOnlyDirectn, Constant::ELEV_PICKUPONLY_DIRECTION_DOWN) == 0)
            {
                $elevator->setInMotion(true);
                $elevator->setCurrentFloor($elevatorCurFloor - 1);
            }
            
            if ($elevator->isInMotion())
            {
               echo "<p>Elevator " . $elevatorId . " moved to floor " . $elevator->getCurrentFloor() . " from Floor " . $elevatorCurFloor . "</p>";
            }
            else
            {
                if ($isPassengerPickup)
                {
                   echo "<p>Elevator " . $elevatorId . " at Floor " . $pickupFloorDest . " for Passenger Pickup Only</p>";
                }
                if ($isFreightPickup)
                {
                   echo "<p>Elevator " . $elevatorId . " at Floor " . $pickupFloorDest . " for Freight Pickup Only</p>";
                }
                $pickupFloors[$pickupFloorDest -1]->setPassengerPickup(false);
                $pickupFloors[$pickupFloorDest -1]->setFreightPickup(false);
            }
        }
    }
    
    private function movePickupDropoff(Elevator $elevator): void
    {
        $elevatorId = $elevator->getElevatorId(); // int
        $elevatorCurFloor = $elevator->getCurrentFloor(); // int
        $elevatorCurDirectn = $elevator->getCurrentDirection(); // int 
        $elevatorPickupOnlyDirectn = $elevator->getPickupOnlyDirection(); // int
        $elevatorPassengerFloorButtons = $elevator->getPassengerElevatorPanel()->getElevatorFloorButtons(); // array of ElevatorFloorButton
        $elevatorFreightFloorButtons = null; // will be an array of ElevatorFloorButton 
        $pickupFloors = null; // will be array of PickupFloor
        $pickupFloorDest = 0; // int
        $dropoffFloorDest = 0; // int
        $isPickup = false; // bool
        $isDropoff = false; // bool
        
        if ($elevator->getFreightElevatorPanel() != null)
        {
            $elevatorFreightFloorButtons = $elevator->getFreightElevatorPanel()->getElevatorFloorButtons();
        }
        // next pickup and/or drop off floor
        
        $pickupFloors = $elevator->getPickupFloors();
                
        if (strcasecmp($elevatorCurDirectn, Constant::ELEV_CURRENT_DIRECTION_UP) == 0)
        {
            //Next pickup floor
            for ($i=0; $i < $this->numberOfFloors; $i++)
            {
                if ($pickupFloors[$i]->isPassengerPickup() || $pickupFloors[$i]->isFreightPickup())
                {
                    $pickupFloorDest = $pickupFloors[$i]->getFloorNumber();
                    break;
                }
            }
            //Next drop off floor
            for ($i=0; $i < $this->numberOfFloors; $i++)
            {
                if ($elevatorPassengerFloorButtons[$i]->isButtonOn())
                {
                    $dropoffFloorDest = $elevatorPassengerFloorButtons[$i]->getFloorNumber();
                    break;
                }
                else if ($elevatorFreightFloorButtons != null && $elevatorFreightFloorButtons[$i]->isButtonOn())
                {
                    $dropoffFloorDest = $elevatorFreightFloorButtons[$i]->getFloorNumber();
                    break;
                }
            }
        }
        else if (strcasecmp($elevatorCurDirectn, Constant::ELEV_CURRENT_DIRECTION_DOWN) == 0)
        {
            //Next pickup floor
            for ( $i=$this->numberOfFloors-1; $i >= 0; $i--)
            {
                if ($pickupFloors[$i]->isPassengerPickup() || $pickupFloors[$i]->isFreightPickup())
                {
                    $pickupFloorDest = $pickupFloors[$i]->getFloorNumber();
                    break;
                }
            }
            //Next drop off floor
            for ($i=$this->numberOfFloors-1; $i >= 0; $i--)
            {
                if ($elevatorPassengerFloorButtons[$i]->isButtonOn())
                {
                    $dropoffFloorDest = $elevatorPassengerFloorButtons[$i]->getFloorNumber();
                    break;
                }
                else if ($elevatorFreightFloorButtons != null && $elevatorFreightFloorButtons[$i]->isButtonOn())
                {
                    $dropoffFloorDest = $elevatorFreightFloorButtons[$i]->getFloorNumber();
                    break;
                }
            }
        }
        
        // Pickup
        if ($pickupFloorDest != 0)
        {
            if ($pickupFloorDest == $elevatorCurFloor)
            {
                $isPickup = true;
            }
            else if (strcasecmp($elevatorCurDirectn, Constant::ELEV_CURRENT_DIRECTION_UP) == 0)
            {
                $isPickup = false;
            }
            else if (strcasecmp($elevatorCurDirectn, Constant::ELEV_CURRENT_DIRECTION_DOWN) == 0)
            {
                $isPickup = false;
            }
        }
        
        //Drop Off
        if ($dropoffFloorDest != 0)
        {
            if ($dropoffFloorDest == $elevatorCurFloor)
            {
                $isDropoff = true;
            }
            else if (strcasecmp($elevatorCurDirectn, Constant::ELEV_CURRENT_DIRECTION_UP) == 0)
            {
                $isDropoff = false;
            }
            else if (strcasecmp($elevatorCurDirectn, Constant::ELEV_CURRENT_DIRECTION_DOWN) == 0)
            {
                $isDropoff = false;
            }
        }
        
        if($isPickup || $isDropoff)
        {
            $elevator->setInMotion(false);
            
            if($isPickup)
            {
                if ($pickupFloors[$pickupFloorDest -1]->isPassengerPickup())
                {
                    echo "<p>Elevator " . $elevatorId . " at Floor " . $pickupFloorDest . " for Passenger Pickup</p>";
                    $pickupFloors[$pickupFloorDest -1]->setPassengerPickup(false);
                }
                if ($pickupFloors[$pickupFloorDest -1]->isFreightPickup())
                {
                    echo "<p>Elevator " . $elevatorId . " at Floor " . $pickupFloorDest . " for Freight Pickup</p>";
                    $pickupFloors[$pickupFloorDest -1]->setFreightPickup(false);
                }
            }
            
            if($isDropoff)
            {
                if ($elevatorPassengerFloorButtons[$dropoffFloorDest - 1]->isButtonOn())
                {
                    echo "<p>Elevator " . $elevatorId . " at Floor " . $dropoffFloorDest . " for Passenger Drop Off</p>";
                    $elevatorPassengerFloorButtons[$dropoffFloorDest - 1]->setButtonOn(false);
                }
                if ($elevatorFreightFloorButtons != null && $elevatorFreightFloorButtons[$dropoffFloorDest - 1]->isButtonOn())
                {
                    echo "<p>Elevator " . $elevatorId . " at Floor " . $dropoffFloorDest . " for Freight Drop Off</p>";
                    $elevatorFreightFloorButtons[$dropoffFloorDest - 1]->setButtonOn(false);
                }
            }
        }
        else
        {
            if ($pickupFloorDest == 0 && $dropoffFloorDest == 0)
            {
                $elevator->setInMotion(false);
                $elevator->setCurrentDirection(Constant::ELEV_CURRENT_DIRECTION_IDLE);
            }
            else if (strcasecmp($elevatorCurDirectn, Constant::ELEV_CURRENT_DIRECTION_UP) == 0)
            {
                $elevator->setInMotion(true);
                $elevator->setCurrentFloor($elevatorCurFloor + 1);
            }
            else if (strcasecmp($elevatorCurDirectn, Constant::ELEV_CURRENT_DIRECTION_DOWN) == 0)
            {
                $elevator->setInMotion(true);
                $elevator->setCurrentFloor($elevatorCurFloor - 1);
            }
            
            if ($elevator->getCurrentFloor() != $elevatorCurFloor)
            {
                echo "<p>Elevator " . $elevatorId . " moved to floor " . $elevator->getCurrentFloor() . " from Floor " . $elevatorCurFloor . "</p>";
            }
            
            if (strcasecmp($elevator->getCurrentDirection(), Constant::ELEV_CURRENT_DIRECTION_IDLE) == 0 && $elevator->getCurrentFloor() == $elevatorCurFloor)
            {
                echo "<p>Elevator " . $elevatorId . " at floor " . $elevatorCurFloor . " in Idle State</p>";
            }

        }
        
    }
    
    private function move(): void
    {
        foreach ( $this->elevators as $elevator)
        {
            $elevatorId = $elevator->getElevatorId(); // int
            $elevatorCurFloor = $elevator->getCurrentFloor(); // int
            $elevatorCurDirectn = $elevator->getCurrentDirection(); // int
            $elevatorPickupOnlyDirectn = $elevator->getPickupOnlyDirection(); // int
            $pickupFloors = null; // array of PickupFloor
            $pickupFloorDest = 0; // int
            
            $pickupFloors = $elevator->getPickupFloors();
            
            if ($elevator->isPickupOnly())
            {
                $this->movePickupOnly($elevator);
            }
            else
            {
                $this->movePickupDropoff($elevator);
            }
        }
    }
    
    public function controller(): void
    {
        $maxInterationCount = 10; // int
        $iterationCount = 1; // int 
        // TODO controller logic
        
        $this->setupTestState();
        
        // controller will run every controllerRunInterval seconds
        // If I was going to have a complete elevator simulator, I'd probably run this in a separate thread.
        //I'd also create a singleton synchronized class named something like ControllerRunStatus that would
        // contain the runStatus from some other thread which would get the class lock when it needed to change
        // the current status of this controller class and then release it after it set it.
        
        $this->controllerRunning = true;
        while ($iterationCount <= $maxInterationCount)
        {
            // sleep for controllerRunInterval number of seconds

            echo "<p>=============================</p>";
            echo "<p>Interation # " . $iterationCount . "</p>";

            // manage elevators
            
            echo "<p>Dispatch Elevators</p>";

            $this->dispatch();

            echo "<p>-----------------------------</p>";
            
            // move elevators
            
            echo "<p>Move Elevators</p>";

            $this->move();

            echo "<p>-----------------------------</p>";
            
//            controllerRunning = false; // so while loop exits since this is not a functional simulation.
            
            // at the end of this loop it would get and wait for the lock on ControllerRunStatus to get the
            // changed run status and set it to controllerRunning. This would allow the controller method to
            // it's loop.
            
            $iterationCount++;
        }
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

    public function getElevators(): ?array // array of  Elevator
    {
        return $this->elevators;
    }

    public function setElevators(array $elevators = null): void //input array of Elevator
    {
        $this->elevators = $elevators;
    }

    public function getFloors(): ?array // array of  Floor
    {
        return $this->floors;
    }

    public function setFloors(array $floors = null): void // input array of Floor
    {
        $this->floors = $floors;
    }

    public function getControllerRunInterval(): int
    {
        return $this->controllerRunInterval;
    }

    public function setControllerRunInterval(int $controllerRunInterval): void
    {
        $this->controllerRunInterval = $controllerRunInterval;
    }

    public function isControllerRunning(): bool
    {
        return $this->controllerRunning;
    }

    public function setControllerRunning(bool $controllerRunning): void
    {
        $this->controllerRunning = $controllerRunning;
    }

    public static function main(string ...$args): void
    {
        // TODO Auto-generated method stub

    }

}
?>

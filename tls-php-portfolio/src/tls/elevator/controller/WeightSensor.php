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

class WeightSensor
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
    
    private $currentWeight = 0; // int
    private $maxWeight = Constant::ELEV_PASSENGER_MAX_WEIGHT; // int
    private $elevatorId = 0; // int
    
    // public function __construct()
    // {
    //     this(Constant::ELEV_PASSENGER_MAX_WEIGHT, 1);
    // }
    
    public function __construct(int $maxWeight = Constant::ELEV_PASSENGER_MAX_WEIGHT, int $elevatorId = 1)
    {
        $this->maxWeight = $maxWeight;
        $this->elevatorId = $elevatorId;
    }
    
    public function isUnderWeightLimit(): bool
    {
        $underWeightLimit = false; // bool
        $this->currentWeight = getCurrentWeightFromElevator();
        if ($this->currentWeight <= $this->maxWeight)
        {
            $this->underWeightLimit = true;
        }
        return $this->underWeightLimit;
        
    }
    
    private function getCurrentWeightFromElevator(): int
    {
        // Poll elevatorId for current weight. Since this is not a working simulation with number of people getting on and off
        // elevator, I'm just setting it to 1000 so it compiles. But if this was part of a finished elevator simulator, a random number
        // would be calculated for the weight getting on the elevator and for the weight getting off. The random number of weight getting off 
        // would max out at the currentWeight.
        
        
        return 1000;
    }

    public function getCurrentWeight(): int
    {
        return $this->currentWeight;
    }

    public function setCurrentWeight(int $currentWeight): void
    {
        $this->currentWeight = $currentWeight;
    }

    public function getMaxWeight(): int
    {
        return $this->maxWeight;
    }

    public function setMaxWeight(int $maxWeight): void
    {
        $this->maxWeight = $maxWeight;
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

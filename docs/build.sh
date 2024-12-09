#!/bin/bash -ex

dot ethos.gv -Tpng > ../public/images/ethos.png
dot ethos2.gv -Tpng > ../public/images/ethos2.png
dot acquisition.gv -Tpng > ../public/images/acquisition.png
dot reinforcement.gv -Tpng > ../public/images/reinforcement.png
dot undermining.gv -Tpng > ../public/images/undermining.png
dot activities.gv -Tpng > ../public/images/activities.png

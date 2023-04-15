<?php

namespace Darcher\PineconePhp;

enum PodType: string {
    case S1_X1 = 's1.x1';
    case S1_X2 = 's1.x2';
    case S1_X4 = 's1.x4';
    case S1_X8 = 's1.x8';

    case P1_X1 = 'p1.x1';
    case P1_X2 = 'p1.x2';
    case P1_X4 = 'p1.x4';
    case P1_X8 = 'p1.x8';

    case P2_X1 = 'p2.x1';
    case P2_X2 = 'p2.x2';
    case P2_X4 = 'p2.x4';
    case P2_X8 = 'p2.x8';
}
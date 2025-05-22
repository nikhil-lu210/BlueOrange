<?php

namespace App\Enums;

enum BloodGroup: string
{
    case A_POSITIVE = 'A Positive (A+)';
    case A_NEGATIVE = 'A Negative (A-)';
    case B_POSITIVE = 'B Positive (B+)';
    case B_NEGATIVE = 'B Negative (B-)';
    case AB_POSITIVE = 'AB Positive (AB+)';
    case AB_NEGATIVE = 'AB Negative (AB-)';
    case O_POSITIVE = 'O Positive (O+)';
    case O_NEGATIVE = 'O Negative (O-)';

    case RH_NULL = 'Rh-null (Golden Blood)';
    case BOMBAY = 'Bombay (Oh)';

    case KELL_POS = 'Kell (K+)';
    case KELL_NEG = 'Kell (k-)';
    case DUFFY_A = 'Duffy (Fy a+)';
    case DUFFY_B = 'Duffy (Fy b+)';
    case KIDD_A = 'Kidd (Jk a+)';
    case KIDD_B = 'Kidd (Jk b+)';
    case MNS_MN = 'MNS (M+, N+)';
    case MNS_SS = 'MNS (S+, s+)';
    case LUTHERAN_A = 'Lutheran (Lu a+)';
    case LUTHERAN_B = 'Lutheran (Lu b+)';
    case DIEGO_A = 'Diego (Di a+)';
    case DIEGO_B = 'Diego (Di b+)';
    case LEWIS_A = 'Lewis (Le a+)';
    case LEWIS_B = 'Lewis (Le b+)';

    case P1 = 'P (P1)';
    case P_SMALL = 'P (p)';

    case NA = 'Unknown';
}

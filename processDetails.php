<?php
function getSurahName($surah_number) {
    $surah_names = [
        1 => "Al-Fatihah", 2 => "Al-Baqarah", 3 => "Al-Imran", 4 => "An-Nisa", 5 => "Al-Ma'idah",
        6 => "Al-An'am", 7 => "Al-A'raf", 8 => "Al-Anfal", 9 => "At-Tawbah", 10 => "Yunus",
        11 => "Hud", 12 => "Yusuf", 13 => "Ar-Ra'd", 14 => "Ibrahim", 15 => "Al-Hijr",
        16 => "An-Nahl", 17 => "Al-Isra", 18 => "Al-Kahf", 19 => "Maryam", 20 => "Ta-Ha",
        21 => "Al-Anbiya", 22 => "Al-Hajj", 23 => "Al-Mu'minun", 24 => "An-Nur", 25 => "Al-Furqan",
        26 => "Ash-Shu'ara", 27 => "An-Naml", 28 => "Al-Qasas", 29 => "Al-Ankabut", 30 => "Ar-Rum",
        31 => "Luqman", 32 => "As-Sajda", 33 => "Al-Ahzab", 34 => "Saba", 35 => "Fatir",
        36 => "Ya-Sin", 37 => "As-Saffat", 38 => "Sad", 39 => "Az-Zumar", 40 => "Ghafir",
        41 => "Fussilat", 42 => "Ash-Shura", 43 => "Az-Zukhruf", 44 => "Ad-Dukhan", 45 => "Al-Jathiya",
        46 => "Al-Ahqaf", 47 => "Muhammad", 48 => "Al-Fath", 49 => "Al-Hujurat", 50 => "Qaf",
        51 => "Adh-Dhariyat", 52 => "At-Tur", 53 => "An-Najm", 54 => "Al-Qamar", 55 => "Ar-Rahman",
        56 => "Al-Waqi'a", 57 => "Al-Hadid", 58 => "Al-Mujadila", 59 => "Al-Hashr", 60 => "Al-Mumtahina",
        61 => "As-Saff", 62 => "Al-Jumu'a", 63 => "Al-Munafiqun", 64 => "At-Taghabun", 65 => "At-Talaq",
        66 => "At-Tahrim", 67 => "Al-Mulk", 68 => "Al-Qalam", 69 => "Al-Haqqa", 70 => "Al-Ma'arij",
        71 => "Nuh", 72 => "Al-Jinn", 73 => "Al-Muzzammil", 74 => "Al-Muddathir", 75 => "Al-Qiyama",
        76 => "Al-Insan", 77 => "Al-Mursalat", 78 => "An-Naba", 79 => "An-Nazi'at", 80 => "Abasa",
        81 => "At-Takwir", 82 => "Al-Infitar", 83 => "Al-Mutaffifin", 84 => "Al-Inshiqaq", 85 => "Al-Buruj",
        86 => "At-Tariq", 87 => "Al-A'la", 88 => "Al-Ghashiya", 89 => "Al-Fajr", 90 => "Al-Balad",
        91 => "Ash-Shams", 92 => "Al-Lail", 93 => "Ad-Duha", 94 => "Ash-Sharh", 95 => "At-Tin",
        96 => "Al-Alaq", 97 => "Al-Qadr", 98 => "Al-Bayyina", 99 => "Az-Zalzala", 100 => "Al-Adiyat",
        101 => "Al-Qari'a", 102 => "At-Takathur", 103 => "Al-Asr", 104 => "Al-Humaza", 105 => "Al-Fil",
        106 => "Quraish", 107 => "Al-Ma'un", 108 => "Al-Kawthar", 109 => "Al-Kafiroon", 110 => "An-Nasr",
        111 => "Al-Masad", 112 => "Al-Ikhlas", 113 => "Al-Falaq", 114 => "An-Nas"
    ];
    return isset($surah_names[$surah_number]) ? $surah_names[$surah_number] : "Unknown Surah";
}

function calculateJuzu($page) {
    if ($page < 22) {
        return 1;
    } elseif ($page < 42) {
        return 2;
    } elseif ($page < 62) {
        return 3;
    } elseif ($page < 82) {
        return 4;
    } elseif ($page < 102) {
        return 5;
    } elseif ($page < 122) {
        return 6;
    } elseif ($page < 142) {
        return 7;
    } elseif ($page < 162) {
        return 8;
    } elseif ($page < 182) {
        return 9;
    } elseif ($page < 202) {
        return 10;
    } elseif ($page < 222) {
        return 11;
    } elseif ($page < 242) {
        return 12;
    } elseif ($page < 262) {
        return 13;
    } elseif ($page < 282) {
        return 14;
    } elseif ($page < 302) {
        return 15;
    } elseif ($page < 322) {
        return 16;
    } elseif ($page < 342) {
        return 17;
    } elseif ($page < 362) {
        return 18;
    } elseif ($page < 382) {
        return 19;
    } elseif ($page < 402) {
        return 20;
    } elseif ($page < 422) {
        return 21;
    } elseif ($page < 442) {
        return 22;
    } elseif ($page < 462) {
        return 23;
    } elseif ($page < 482) {
        return 24;
    } elseif ($page < 502) {
        return 25;
    } elseif ($page < 522) {
        return 26;
    } elseif ($page < 542) {
        return 27;
    } elseif ($page < 562) {
        return 28;
    } elseif ($page < 582) {
        return 29;
    } else {
        return 30;
    }
}

function calculateSurah($page) {
    $page_ranges = [
        [1, 1, 1], [2, 2, 49], [3, 50, 76], [4, 77, 106], [5, 107, 127],
        [6, 128, 151], [7, 152, 177], [8, 178, 187], [9, 188, 207], [10, 208, 221],
        [11, 222, 235], [12, 236, 249], [13, 250, 255], [14, 256, 261], [15, 262, 267],
        [16, 268, 280], [17, 281, 293], [18, 294, 304], [19, 305, 312], [20, 313, 321],
        [21, 322, 331], [22, 332, 341], [23, 342, 349], [24, 350, 359], [25, 360, 369],
        [26, 370, 377], [27, 378, 385], [28, 386, 396], [29, 397, 404], [30, 405, 411],
        [31, 412, 416], [32, 417, 419], [33, 420, 427], [34, 428, 434], [35, 435, 440],
        [36, 441, 445], [37, 446, 451], [38, 452, 458], [39, 459, 467], [40, 468, 477],
        [41, 478, 482], [42, 483, 489], [43, 490, 495], [44, 496, 498], [45, 499, 502],
        [46, 503, 506], [47, 507, 510], [48, 511, 515], [49, 516, 518], [50, 519, 520],
        [51, 521, 523], [52, 524, 526], [53, 527, 529], [54, 530, 532], [55, 533, 534],
        [56, 535, 537], [57, 538, 541], [58, 542, 545], [59, 546, 548], [60, 549, 551],
        [61, 552, 553], [62, 554, 555], [63, 556, 557], [64, 558, 560], [65, 561, 563],
        [66, 564, 566], [67, 567, 568], [68, 569, 571], [69, 572, 573], [70, 574, 575],
        [71, 576, 577], [72, 578, 579], [73, 580, 581], [74, 582, 583], [75, 584, 585],
        [76, 586, 587], [77, 588, 589], [78, 590, 591], [79, 592, 593], [80, 594, 595],
        [81, 596, 597], [82, 598, 599], [83, 600, 601], [84, 602, 603], [114, 604, 604]
    ];

    foreach ($page_ranges as $range) {
        if ($page >= $range[1] && $page <= $range[2]) {
            return $range[0];
        }
    }
    return 1; // Default to Surah Al-Fatihah if not found
}

function getStatusDescription($status) {
    $statuses = [
        'p' => 'Pass',
        'f' => 'Not Pass'
    ];
    return isset($statuses[$status]) ? $statuses[$status] : 'Unknown Status';
}

function getSessionDescription($session) {
    $sessions = [
        'd' => 'Day',
        'n' => 'Night'
    ];
    return isset($sessions[$session]) ? $sessions[$session] : 'Unknown Session';
}


function getSessionByTime($time) {
    $hour = (int)date('H', strtotime($time));
    return ($hour >= 6 && $hour < 18) ? 'd' : 'n';
}

if (!function_exists('getStatusDescription')) {
    function getStatusDescription($status) {
        return $status == 'p' ? 'Pass' : 'Not Pass';
    }
}
?>

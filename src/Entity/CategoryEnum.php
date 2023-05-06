<?php

namespace App\Entity;

enum CategoryEnum: string
{
    case NOTES = "notes";
    case EXAMS= "exams";
    case EXERCISES = "exercises";
    case PRACTICES = "practices";
    case PAPERS = "papers";
    case TEST = "test";
    case OTHERS = "others";
}

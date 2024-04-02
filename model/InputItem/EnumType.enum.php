<?php
enum EnumType: int
{
        // PR
    case PR = 1;
        // 集客
    case MARKETING = 2;
        // 求人
    case RECRUITMENT = 3;

    public function text()
    {
        return match ($this) {
            EnumType::PR => "PR",
            EnumType::MARKETING => "集客",
            EnumType::RECRUITMENT => "求人",
        };
    }
}

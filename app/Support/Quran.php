<?php

namespace App\Support;

class Quran
{
    /**
     * Daftar 114 Surah Al-Qur'an lengkap beserta nomor surah.
     *
     * @return list<string>
     */
    public static function surahs(): array
    {
        $raw = [
            'Al-Fatihah', 'Al-Baqarah', 'Ali Imran', 'An-Nisa', 'Al-Maidah',
            'Al-Anam', 'Al-Araf', 'Al-Anfal', 'At-Taubah', 'Yunus',
            'Hud', 'Yusuf', 'Ar-Rad', 'Ibrahim', 'Al-Hijr',
            'An-Nahl', 'Al-Isra', 'Al-Kahfi', 'Maryam', 'Taha',
            'Al-Anbiya', 'Al-Hajj', 'Al-Muminun', 'An-Nur', 'Al-Furqan',
            'Asy-Syuara', 'An-Naml', 'Al-Qasas', 'Al-Ankabut', 'Ar-Rum',
            'Luqman', 'As-Sajdah', 'Al-Ahzab', 'Saba', 'Fatir',
            'Yasin', 'As-Saffat', 'Sad', 'Az-Zumar', 'Gafir',
            'Fussilat', 'Asy-Syura', 'Az-Zukhruf', 'Ad-Dukhan', 'Al-Jasiyah',
            'Al-Ahqaf', 'Muhammad', 'Al-Fath', 'Al-Hujurat', 'Qaf',
            'Az-Zariyat', 'At-Tur', 'An-Najm', 'Al-Qamar', 'Ar-Rahman',
            'Al-Waqiah', 'Al-Hadid', 'Al-Mujadilah', 'Al-Hasyr', 'Al-Mumtahanah',
            'As-Saff', 'Al-Jumuah', 'Al-Munafiqun', 'At-Tagabun', 'At-Talaq',
            'At-Tahrim', 'Al-Mulk', 'Al-Qalam', 'Al-Haqqah', 'Al-Maarij',
            'Nuh', 'Al-Jinn', 'Al-Muzzammil', 'Al-Muddassir', 'Al-Qiyamah',
            'Al-Insan', 'Al-Mursalat', 'An-Naba', 'An-Naziat', 'Abasa',
            'At-Takwir', 'Al-Infitar', 'Al-Mutaffifin', 'Al-Insyiqaq', 'Al-Buruj',
            'At-Tariq', 'Al-Ala', 'Al-Gasyiyah', 'Al-Fajr', 'Al-Balad',
            'Asy-Syams', 'Al-Lail', 'Ad-Duha', 'Asy-Syarh', 'At-Tin',
            'Al-Alaq', 'Al-Qadr', 'Al-Bayyinah', 'Az-Zalzalah', 'Al-Adiyat',
            'Al-Qariah', 'At-Takasur', 'Al-Asr', 'Al-Humazah', 'Al-Fil',
            'Quraisy', 'Al-Maun', 'Al-Kausar', 'Al-Kafirun', 'An-Nasr',
            'Al-Masad', 'Al-Ikhlas', 'Al-Falaq', 'An-Nas',
        ];

        $result = [];
        foreach ($raw as $index => $name) {
            $result[] = ($index + 1).'. '.$name;
        }

        return $result;
    }

    /**
     * Kategori setoran yang valid.
     *
     * @return list<string>
     */
    public static function categories(): array
    {
        return ['Ziyadah', 'Murojaah'];
    }

    /**
     * Jurusan yang tersedia di MAN 1 Probolinggo.
     *
     * @return list<string>
     */
    public static function majors(): array
    {
        return ['MIPA', 'IPS', 'Keagamaan', 'Bahasa'];
    }
}

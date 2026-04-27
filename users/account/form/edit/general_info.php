<?php
  
if (post('ok_gen')){
  
  valid::create(array(
    
    'GEN_NAME' => ['name', 'text', [0, 15], 'Имя', 0],
    'GEN_SURNAME' => ['surname', 'text', [0, 15], 'Фамилия', 0],
    'GEN_SEX' => ['sex', 'number', [1, 2], 'Пол'],
    'GEN_D_R' => ['d_r', 'number', [0, 31], 'День рождения'],
    'GEN_M_R' => ['m_r', 'number', [0, 12], 'Месяц рождения'],
    'GEN_G_R' => ['g_r', 'number', [0, 2030], 'Год рождения']
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect('/account/form/?id='.$account['ID'].'&get=general_info&'.TOKEN_URL);
  
  }
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `G_R` = ?, `M_R` = ?, `D_R` = ?, `NAME` = ?, `SURNAME` = ? WHERE `USER_ID` = ? LIMIT 1", [GEN_G_R, GEN_M_R, GEN_D_R, GEN_NAME, GEN_SURNAME, $account['ID']]);
  db::get_set("UPDATE `USERS` SET `SEX` = ? WHERE `ID` = ? LIMIT 1", [GEN_SEX, $account['ID']]);
  
  success('Изменения успешно приняты');
  redirect('/account/form/?id='.$account['ID']);
  
}
  
?>

<div class='list'>  
<form method='post' class='ajax-form' action='/account/form/?id=<?=$account['ID']?>&get=general_info&<?=TOKEN_URL?>'>
<?=html::input('name', 'Имя', null, null, tabs($settings['NAME']), 'form-control-100', 'type', null, 'address-book-o')?>
<?=html::input('surname', 'Фамилия', null, null, tabs($settings['SURNAME']), 'form-control-100', 'type', null, 'address-book-o')?>
<?=html::select('d_r', array(
  0 => ['Не выбрано', ($settings['D_R'] == 0 ? "selected" : null)], 
  1 => ['01', ($settings['D_R'] == 1 ? "selected" : null)],
  2 => ['02', ($settings['D_R'] == 2 ? "selected" : null)], 
  3 => ['03', ($settings['D_R'] == 3 ? "selected" : null)],
  4 => ['04', ($settings['D_R'] == 4 ? "selected" : null)], 
  5 => ['05', ($settings['D_R'] == 5 ? "selected" : null)],
  6 => ['06', ($settings['D_R'] == 6 ? "selected" : null)], 
  7 => ['07', ($settings['D_R'] == 7 ? "selected" : null)],
  8 => ['08', ($settings['D_R'] == 8 ? "selected" : null)], 
  9 => ['09', ($settings['D_R'] == 9 ? "selected" : null)],
  10 => ['10', ($settings['D_R'] == 10 ? "selected" : null)], 
  11 => ['11', ($settings['D_R'] == 11 ? "selected" : null)],
  12 => ['12', ($settings['D_R'] == 12 ? "selected" : null)], 
  13 => ['13', ($settings['D_R'] == 13 ? "selected" : null)],
  14 => ['14', ($settings['D_R'] == 14 ? "selected" : null)], 
  15 => ['15', ($settings['D_R'] == 15 ? "selected" : null)],
  16 => ['16', ($settings['D_R'] == 16 ? "selected" : null)], 
  17 => ['17', ($settings['D_R'] == 17 ? "selected" : null)],
  18 => ['18', ($settings['D_R'] == 18 ? "selected" : null)], 
  19 => ['19', ($settings['D_R'] == 19 ? "selected" : null)],
  20 => ['20', ($settings['D_R'] == 20 ? "selected" : null)], 
  21 => ['21', ($settings['D_R'] == 21 ? "selected" : null)],
  22 => ['22', ($settings['D_R'] == 22 ? "selected" : null)], 
  23 => ['23', ($settings['D_R'] == 23 ? "selected" : null)],
  24 => ['24', ($settings['D_R'] == 24 ? "selected" : null)], 
  25 => ['25', ($settings['D_R'] == 25 ? "selected" : null)],
  26 => ['26', ($settings['D_R'] == 26 ? "selected" : null)], 
  27 => ['27', ($settings['D_R'] == 27 ? "selected" : null)],
  28 => ['28', ($settings['D_R'] == 28 ? "selected" : null)], 
  29 => ['29', ($settings['D_R'] == 29 ? "selected" : null)],
  30 => ['30', ($settings['D_R'] == 30 ? "selected" : null)], 
  31 => ['31', ($settings['D_R'] == 31 ? "selected" : null)]
), 'День рождения', 'form-control-100-modify-select', 'clock-o')?>
<?=html::select('m_r', array(
  0 => ['Не выбрано', ($settings['M_R'] == 0 ? "selected" : null)], 
  1 => ['Января', ($settings['M_R'] == 1 ? "selected" : null)],
  2 => ['Февраля', ($settings['M_R'] == 2 ? "selected" : null)], 
  3 => ['Марта', ($settings['M_R'] == 3 ? "selected" : null)],
  4 => ['Апреля', ($settings['M_R'] == 4 ? "selected" : null)], 
  5 => ['Мая', ($settings['M_R'] == 5 ? "selected" : null)],
  6 => ['Июня', ($settings['M_R'] == 6 ? "selected" : null)], 
  7 => ['Июля', ($settings['M_R'] == 7 ? "selected" : null)],
  8 => ['Августа', ($settings['M_R'] == 8 ? "selected" : null)], 
  9 => ['Сентября', ($settings['M_R'] == 9 ? "selected" : null)],
  10 => ['Октября', ($settings['M_R'] == 10 ? "selected" : null)], 
  11 => ['Ноября', ($settings['M_R'] == 11 ? "selected" : null)], 
  12 => ['Декабря', ($settings['M_R'] == 12 ? "selected" : null)]
), 'Месяц рождения', 'form-control-100-modify-select', 'clock-o')?>
<?=html::select('g_r', array(
  0 => ['Не выбрано', ($settings['G_R'] == 0 ? "selected" : null)], 
  2020 => ['2020', ($settings['G_R'] == 2020 ? "selected" : null)],
  2019 => ['2019', ($settings['G_R'] == 2019 ? "selected" : null)], 
  2018 => ['2018', ($settings['G_R'] == 2018 ? "selected" : null)],
  2017 => ['2017', ($settings['G_R'] == 2017 ? "selected" : null)], 
  2016 => ['2016', ($settings['G_R'] == 2016 ? "selected" : null)],
  2015 => ['2015', ($settings['G_R'] == 2015 ? "selected" : null)], 
  2014 => ['2014', ($settings['G_R'] == 2014 ? "selected" : null)],
  2013 => ['2013', ($settings['G_R'] == 2013 ? "selected" : null)], 
  2012 => ['2012', ($settings['G_R'] == 2012 ? "selected" : null)],
  2011 => ['2011', ($settings['G_R'] == 2011 ? "selected" : null)], 
  2010 => ['2010', ($settings['G_R'] == 2010 ? "selected" : null)], 
  2009 => ['2009', ($settings['G_R'] == 2009 ? "selected" : null)], 
  2008 => ['2008', ($settings['G_R'] == 2008 ? "selected" : null)],
  2007 => ['2007', ($settings['G_R'] == 2007 ? "selected" : null)], 
  2006 => ['2006', ($settings['G_R'] == 2006 ? "selected" : null)],
  2005 => ['2005', ($settings['G_R'] == 2005 ? "selected" : null)], 
  2004 => ['2004', ($settings['G_R'] == 2004 ? "selected" : null)],
  2003 => ['2003', ($settings['G_R'] == 2003 ? "selected" : null)], 
  2002 => ['2002', ($settings['G_R'] == 2002 ? "selected" : null)],
  2001 => ['2001', ($settings['G_R'] == 2001 ? "selected" : null)], 
  2000 => ['2000', ($settings['G_R'] == 2000 ? "selected" : null)],
  1999 => ['1999', ($settings['G_R'] == 1999 ? "selected" : null)], 
  1998 => ['1998', ($settings['G_R'] == 1998 ? "selected" : null)], 
  1997 => ['1997', ($settings['G_R'] == 1997 ? "selected" : null)], 
  1996 => ['1996', ($settings['G_R'] == 1996 ? "selected" : null)],
  1995 => ['1995', ($settings['G_R'] == 1995 ? "selected" : null)], 
  1994 => ['1994', ($settings['G_R'] == 1994 ? "selected" : null)],
  1993 => ['1993', ($settings['G_R'] == 1993 ? "selected" : null)], 
  1992 => ['1992', ($settings['G_R'] == 1992 ? "selected" : null)],
  1991 => ['1991', ($settings['G_R'] == 1991 ? "selected" : null)], 
  1990 => ['1990', ($settings['G_R'] == 1990 ? "selected" : null)],
  1989 => ['1989', ($settings['G_R'] == 1989 ? "selected" : null)], 
  1988 => ['1988', ($settings['G_R'] == 1988 ? "selected" : null)],
  1987 => ['1987', ($settings['G_R'] == 1987 ? "selected" : null)], 
  1986 => ['1986', ($settings['G_R'] == 1986 ? "selected" : null)], 
  1985 => ['1985', ($settings['G_R'] == 1985 ? "selected" : null)], 
  1984 => ['1984', ($settings['G_R'] == 1984 ? "selected" : null)], 
  1983 => ['1983', ($settings['G_R'] == 1983 ? "selected" : null)],
  1982 => ['1982', ($settings['G_R'] == 1982 ? "selected" : null)], 
  1981 => ['1981', ($settings['G_R'] == 1981 ? "selected" : null)],
  1980 => ['1980', ($settings['G_R'] == 1980 ? "selected" : null)], 
  1979 => ['1979', ($settings['G_R'] == 1979 ? "selected" : null)],
  1978 => ['1978', ($settings['G_R'] == 1978 ? "selected" : null)], 
  1977 => ['1977', ($settings['G_R'] == 1977 ? "selected" : null)],
  1976 => ['1976', ($settings['G_R'] == 1976 ? "selected" : null)], 
  1975 => ['1975', ($settings['G_R'] == 1975 ? "selected" : null)],
  1974 => ['1974', ($settings['G_R'] == 1974 ? "selected" : null)], 
  1973 => ['1973', ($settings['G_R'] == 1973 ? "selected" : null)], 
  1972 => ['1972', ($settings['G_R'] == 1972 ? "selected" : null)], 
  1971 => ['1971', ($settings['G_R'] == 1971 ? "selected" : null)], 
  1970 => ['1970', ($settings['G_R'] == 1970 ? "selected" : null)],
  1969 => ['1969', ($settings['G_R'] == 1969 ? "selected" : null)], 
  1968 => ['1968', ($settings['G_R'] == 1968 ? "selected" : null)],
  1967 => ['1967', ($settings['G_R'] == 1967 ? "selected" : null)], 
  1966 => ['1966', ($settings['G_R'] == 1966 ? "selected" : null)],
  1965 => ['1965', ($settings['G_R'] == 1965 ? "selected" : null)], 
  1964 => ['1964', ($settings['G_R'] == 1964 ? "selected" : null)],
  1963 => ['1963', ($settings['G_R'] == 1963 ? "selected" : null)], 
  1962 => ['1962', ($settings['G_R'] == 1962 ? "selected" : null)],
  1961 => ['1961', ($settings['G_R'] == 1961 ? "selected" : null)], 
  1960 => ['1960', ($settings['G_R'] == 1960 ? "selected" : null)], 
  1959 => ['1959', ($settings['G_R'] == 1959 ? "selected" : null)],  
  1958 => ['1958', ($settings['G_R'] == 1958 ? "selected" : null)],
  1957 => ['1957', ($settings['G_R'] == 1957 ? "selected" : null)], 
  1956 => ['1956', ($settings['G_R'] == 1956 ? "selected" : null)],
  1955 => ['1955', ($settings['G_R'] == 1955 ? "selected" : null)], 
  1954 => ['1954', ($settings['G_R'] == 1954 ? "selected" : null)],
  1953 => ['1953', ($settings['G_R'] == 1953 ? "selected" : null)], 
  1952 => ['1952', ($settings['G_R'] == 1952 ? "selected" : null)],
  1951 => ['1951', ($settings['G_R'] == 1951 ? "selected" : null)], 
  1950 => ['1950', ($settings['G_R'] == 1950 ? "selected" : null)]
), 'Год рождения', 'form-control-100-modify-select', 'clock-o')?>
<?=html::select('sex', array(
  1 => ['Мужской', ($account['SEX'] == 1 ? "selected" : null)], 
  2 => ['Женский', ($account['SEX'] == 2 ? "selected" : null)]
), 'Пол', 'form-control-100-modify-select', 'venus-mars')?>
<?=html::button('button ajax-button', 'ok_gen', 'save', 'Сохранить изменения')?> 
</form>  
</div>
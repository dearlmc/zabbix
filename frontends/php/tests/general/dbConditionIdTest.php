<?php
/*
** Zabbix
** Copyright (C) 2001-2018 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/


require_once dirname(__FILE__).'/../../include/func.inc.php';
require_once dirname(__FILE__).'/../include/CTest.php';
require_once dirname(__FILE__).'/../../include/db.inc.php';

class dbConditionIdTest extends CTest {

	public static function provider() {
		return [
			[
				['field', []],
				'1=0'
			],
			[
				['field', range(1, 100)],
				"field BETWEEN '1' AND '100'"
			],
			[
				['field', [0]],
				"field IS NULL"
			],
			[
				['field', [1]],
				"field='1'"
			],
			[
				['field', []],
				'1=0'
			],
			[
				['field', [true]],
				'1=0'
			],
			[
				['field', [0, 1]],
				"(field='1' OR field IS NULL)"
			],
			[
				['field', [0, 1, 2, 3]],
				"(field IN ('1','2','3') OR field IS NULL)"
			],
			[
				['field', [0, 1, 2, 3, 5, 6, 7, 8, 9, 10]],
				"(field BETWEEN '5' AND '10' OR field IN ('1','2','3') OR field IS NULL)"
			],
			[
				['field', [0, 1, 2, 3, 5, 6, 7, 8, 9, 10], true],
				"NOT field BETWEEN '5' AND '10' AND field NOT IN ('1','2','3') AND field IS NOT NULL"
			],
			[
				['field', [1, 0]],
				"(field='1' OR field IS NULL)"
			],
			[
				['field', [1], true],
				"field!='1'"
			],
			[
				['field', range(1, 20, 5)],
				"field IN ('1','6','11','16')"
			],
			[
				['field', range(1, 20, 5), true],
				"field NOT IN ('1','6','11','16')"
			],
			[
				['field', range(1, 100, 10)],
				"field IN ('1','11','21','31','41','51','61','71','81','91')"
			],
			[
				['field', array_merge(range(1, 10), range(20, 30))],
				"(field BETWEEN '1' AND '10' OR field BETWEEN '20' AND '30')"
			],
			[
				['field', array_merge(range(1, 10), range(20, 30)), true],
				"NOT field BETWEEN '1' AND '10' AND NOT field BETWEEN '20' AND '30'"
			],
			[
				['field', array_merge(range(1, 4), range(1, 4), range(20, 30))],
				"(field BETWEEN '20' AND '30' OR field IN ('1','2','3','4'))"
			],
			[
				['field', array_merge(range(1, 4), range(1, 4), range(20, 30)), true],
				"NOT field BETWEEN '20' AND '30' AND field NOT IN ('1','2','3','4')"
			],
			[
				['field', array_merge(range(20, 30), [10])],
				"(field BETWEEN '20' AND '30' OR field='10')"
			],
			[
				['field', array_merge(range(20, 30), [10]), true],
				"NOT field BETWEEN '20' AND '30' AND field!='10'"
			],
			[
				['field', ['9223372036854775802', '9223372036854775802', '9223372036854775803', '9223372036854775804', '9223372036854775805', '9223372036854775806']],
				"field BETWEEN '9223372036854775802' AND '9223372036854775806'"
			],
			[
				['field', ['9223372036854775802', '9223372036854775803', '9223372036854775804', '9223372036854775805', '9223372036854775806', '9223372036854775807']],
				"field BETWEEN '9223372036854775802' AND '9223372036854775807'"
			],
			[
				['field', ['9223372036854775807', '9223372036854775806', '9223372036854775805', '9223372036854775804', '9223372036854775803', '9223372036854775802']],
				"field BETWEEN '9223372036854775802' AND '9223372036854775807'"
			],
			[
				['field', ['id_001' => 1]],
				"field='1'"
			],
			[
				['field', ['id_001' => '1', 'id_002' => '2', 'id_003' => '3', 'id_004' => '4', 'id_005' => '5', 'id_006' => '6']],
				"field BETWEEN '1' AND '6'"
			],
			[
				['field', ['1', '7', '90', '91', '100', '400'], false, false],
				"field IN ('1','7','90','91','100','400')"
			],
			[
				['field', range(1, 1900, 2)],
				"field IN ('1','3','5','7','9','11','13','15','17','19','21','23','25','27','29','31','33','35','37','39','41','43','45','47','49','51','53','55','57','59','61','63','65','67','69','71','73','75','77','79','81','83','85','87','89','91','93','95','97','99','101','103','105','107','109','111','113','115','117','119','121','123','125','127','129','131','133','135','137','139','141','143','145','147','149','151','153','155','157','159','161','163','165','167','169','171','173','175','177','179','181','183','185','187','189','191','193','195','197','199','201','203','205','207','209','211','213','215','217','219','221','223','225','227','229','231','233','235','237','239','241','243','245','247','249','251','253','255','257','259','261','263','265','267','269','271','273','275','277','279','281','283','285','287','289','291','293','295','297','299','301','303','305','307','309','311','313','315','317','319','321','323','325','327','329','331','333','335','337','339','341','343','345','347','349','351','353','355','357','359','361','363','365','367','369','371','373','375','377','379','381','383','385','387','389','391','393','395','397','399','401','403','405','407','409','411','413','415','417','419','421','423','425','427','429','431','433','435','437','439','441','443','445','447','449','451','453','455','457','459','461','463','465','467','469','471','473','475','477','479','481','483','485','487','489','491','493','495','497','499','501','503','505','507','509','511','513','515','517','519','521','523','525','527','529','531','533','535','537','539','541','543','545','547','549','551','553','555','557','559','561','563','565','567','569','571','573','575','577','579','581','583','585','587','589','591','593','595','597','599','601','603','605','607','609','611','613','615','617','619','621','623','625','627','629','631','633','635','637','639','641','643','645','647','649','651','653','655','657','659','661','663','665','667','669','671','673','675','677','679','681','683','685','687','689','691','693','695','697','699','701','703','705','707','709','711','713','715','717','719','721','723','725','727','729','731','733','735','737','739','741','743','745','747','749','751','753','755','757','759','761','763','765','767','769','771','773','775','777','779','781','783','785','787','789','791','793','795','797','799','801','803','805','807','809','811','813','815','817','819','821','823','825','827','829','831','833','835','837','839','841','843','845','847','849','851','853','855','857','859','861','863','865','867','869','871','873','875','877','879','881','883','885','887','889','891','893','895','897','899','901','903','905','907','909','911','913','915','917','919','921','923','925','927','929','931','933','935','937','939','941','943','945','947','949','951','953','955','957','959','961','963','965','967','969','971','973','975','977','979','981','983','985','987','989','991','993','995','997','999','1001','1003','1005','1007','1009','1011','1013','1015','1017','1019','1021','1023','1025','1027','1029','1031','1033','1035','1037','1039','1041','1043','1045','1047','1049','1051','1053','1055','1057','1059','1061','1063','1065','1067','1069','1071','1073','1075','1077','1079','1081','1083','1085','1087','1089','1091','1093','1095','1097','1099','1101','1103','1105','1107','1109','1111','1113','1115','1117','1119','1121','1123','1125','1127','1129','1131','1133','1135','1137','1139','1141','1143','1145','1147','1149','1151','1153','1155','1157','1159','1161','1163','1165','1167','1169','1171','1173','1175','1177','1179','1181','1183','1185','1187','1189','1191','1193','1195','1197','1199','1201','1203','1205','1207','1209','1211','1213','1215','1217','1219','1221','1223','1225','1227','1229','1231','1233','1235','1237','1239','1241','1243','1245','1247','1249','1251','1253','1255','1257','1259','1261','1263','1265','1267','1269','1271','1273','1275','1277','1279','1281','1283','1285','1287','1289','1291','1293','1295','1297','1299','1301','1303','1305','1307','1309','1311','1313','1315','1317','1319','1321','1323','1325','1327','1329','1331','1333','1335','1337','1339','1341','1343','1345','1347','1349','1351','1353','1355','1357','1359','1361','1363','1365','1367','1369','1371','1373','1375','1377','1379','1381','1383','1385','1387','1389','1391','1393','1395','1397','1399','1401','1403','1405','1407','1409','1411','1413','1415','1417','1419','1421','1423','1425','1427','1429','1431','1433','1435','1437','1439','1441','1443','1445','1447','1449','1451','1453','1455','1457','1459','1461','1463','1465','1467','1469','1471','1473','1475','1477','1479','1481','1483','1485','1487','1489','1491','1493','1495','1497','1499','1501','1503','1505','1507','1509','1511','1513','1515','1517','1519','1521','1523','1525','1527','1529','1531','1533','1535','1537','1539','1541','1543','1545','1547','1549','1551','1553','1555','1557','1559','1561','1563','1565','1567','1569','1571','1573','1575','1577','1579','1581','1583','1585','1587','1589','1591','1593','1595','1597','1599','1601','1603','1605','1607','1609','1611','1613','1615','1617','1619','1621','1623','1625','1627','1629','1631','1633','1635','1637','1639','1641','1643','1645','1647','1649','1651','1653','1655','1657','1659','1661','1663','1665','1667','1669','1671','1673','1675','1677','1679','1681','1683','1685','1687','1689','1691','1693','1695','1697','1699','1701','1703','1705','1707','1709','1711','1713','1715','1717','1719','1721','1723','1725','1727','1729','1731','1733','1735','1737','1739','1741','1743','1745','1747','1749','1751','1753','1755','1757','1759','1761','1763','1765','1767','1769','1771','1773','1775','1777','1779','1781','1783','1785','1787','1789','1791','1793','1795','1797','1799','1801','1803','1805','1807','1809','1811','1813','1815','1817','1819','1821','1823','1825','1827','1829','1831','1833','1835','1837','1839','1841','1843','1845','1847','1849','1851','1853','1855','1857','1859','1861','1863','1865','1867','1869','1871','1873','1875','1877','1879','1881','1883','1885','1887','1889','1891','1893','1895','1897','1899')",
			],
			[
				['field', array_merge(range(1, 1902, 2), range(2000,3000))],
				"(field BETWEEN '2000' AND '3000' OR field IN ('1','3','5','7','9','11','13','15','17','19','21','23','25','27','29','31','33','35','37','39','41','43','45','47','49','51','53','55','57','59','61','63','65','67','69','71','73','75','77','79','81','83','85','87','89','91','93','95','97','99','101','103','105','107','109','111','113','115','117','119','121','123','125','127','129','131','133','135','137','139','141','143','145','147','149','151','153','155','157','159','161','163','165','167','169','171','173','175','177','179','181','183','185','187','189','191','193','195','197','199','201','203','205','207','209','211','213','215','217','219','221','223','225','227','229','231','233','235','237','239','241','243','245','247','249','251','253','255','257','259','261','263','265','267','269','271','273','275','277','279','281','283','285','287','289','291','293','295','297','299','301','303','305','307','309','311','313','315','317','319','321','323','325','327','329','331','333','335','337','339','341','343','345','347','349','351','353','355','357','359','361','363','365','367','369','371','373','375','377','379','381','383','385','387','389','391','393','395','397','399','401','403','405','407','409','411','413','415','417','419','421','423','425','427','429','431','433','435','437','439','441','443','445','447','449','451','453','455','457','459','461','463','465','467','469','471','473','475','477','479','481','483','485','487','489','491','493','495','497','499','501','503','505','507','509','511','513','515','517','519','521','523','525','527','529','531','533','535','537','539','541','543','545','547','549','551','553','555','557','559','561','563','565','567','569','571','573','575','577','579','581','583','585','587','589','591','593','595','597','599','601','603','605','607','609','611','613','615','617','619','621','623','625','627','629','631','633','635','637','639','641','643','645','647','649','651','653','655','657','659','661','663','665','667','669','671','673','675','677','679','681','683','685','687','689','691','693','695','697','699','701','703','705','707','709','711','713','715','717','719','721','723','725','727','729','731','733','735','737','739','741','743','745','747','749','751','753','755','757','759','761','763','765','767','769','771','773','775','777','779','781','783','785','787','789','791','793','795','797','799','801','803','805','807','809','811','813','815','817','819','821','823','825','827','829','831','833','835','837','839','841','843','845','847','849','851','853','855','857','859','861','863','865','867','869','871','873','875','877','879','881','883','885','887','889','891','893','895','897','899','901','903','905','907','909','911','913','915','917','919','921','923','925','927','929','931','933','935','937','939','941','943','945','947','949','951','953','955','957','959','961','963','965','967','969','971','973','975','977','979','981','983','985','987','989','991','993','995','997','999','1001','1003','1005','1007','1009','1011','1013','1015','1017','1019','1021','1023','1025','1027','1029','1031','1033','1035','1037','1039','1041','1043','1045','1047','1049','1051','1053','1055','1057','1059','1061','1063','1065','1067','1069','1071','1073','1075','1077','1079','1081','1083','1085','1087','1089','1091','1093','1095','1097','1099','1101','1103','1105','1107','1109','1111','1113','1115','1117','1119','1121','1123','1125','1127','1129','1131','1133','1135','1137','1139','1141','1143','1145','1147','1149','1151','1153','1155','1157','1159','1161','1163','1165','1167','1169','1171','1173','1175','1177','1179','1181','1183','1185','1187','1189','1191','1193','1195','1197','1199','1201','1203','1205','1207','1209','1211','1213','1215','1217','1219','1221','1223','1225','1227','1229','1231','1233','1235','1237','1239','1241','1243','1245','1247','1249','1251','1253','1255','1257','1259','1261','1263','1265','1267','1269','1271','1273','1275','1277','1279','1281','1283','1285','1287','1289','1291','1293','1295','1297','1299','1301','1303','1305','1307','1309','1311','1313','1315','1317','1319','1321','1323','1325','1327','1329','1331','1333','1335','1337','1339','1341','1343','1345','1347','1349','1351','1353','1355','1357','1359','1361','1363','1365','1367','1369','1371','1373','1375','1377','1379','1381','1383','1385','1387','1389','1391','1393','1395','1397','1399','1401','1403','1405','1407','1409','1411','1413','1415','1417','1419','1421','1423','1425','1427','1429','1431','1433','1435','1437','1439','1441','1443','1445','1447','1449','1451','1453','1455','1457','1459','1461','1463','1465','1467','1469','1471','1473','1475','1477','1479','1481','1483','1485','1487','1489','1491','1493','1495','1497','1499','1501','1503','1505','1507','1509','1511','1513','1515','1517','1519','1521','1523','1525','1527','1529','1531','1533','1535','1537','1539','1541','1543','1545','1547','1549','1551','1553','1555','1557','1559','1561','1563','1565','1567','1569','1571','1573','1575','1577','1579','1581','1583','1585','1587','1589','1591','1593','1595','1597','1599','1601','1603','1605','1607','1609','1611','1613','1615','1617','1619','1621','1623','1625','1627','1629','1631','1633','1635','1637','1639','1641','1643','1645','1647','1649','1651','1653','1655','1657','1659','1661','1663','1665','1667','1669','1671','1673','1675','1677','1679','1681','1683','1685','1687','1689','1691','1693','1695','1697','1699','1701','1703','1705','1707','1709','1711','1713','1715','1717','1719','1721','1723','1725','1727','1729','1731','1733','1735','1737','1739','1741','1743','1745','1747','1749','1751','1753','1755','1757','1759','1761','1763','1765','1767','1769','1771','1773','1775','1777','1779','1781','1783','1785','1787','1789','1791','1793','1795','1797','1799','1801','1803','1805','1807','1809','1811','1813','1815','1817','1819','1821','1823','1825','1827','1829','1831','1833','1835','1837','1839','1841','1843','1845','1847','1849','1851','1853','1855','1857','1859','1861','1863','1865','1867','1869','1871','1873','1875','1877','1879','1881','1883','1885','1887','1889','1891','1893','1895','1897','1899') OR field='1901')",
			]
		];
	}

	/**
	 * @dataProvider provider
	 */
	public function test($params, $expectedResult) {
		$result = call_user_func_array('dbConditionId', $params);

		$this->assertSame($expectedResult, $result);
	}
}

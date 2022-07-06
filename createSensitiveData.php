<?php
        $server = "database";
        $username = "user";
        $password = "password";
        $db_name = "db";

        $conn = db_connection($server, $username, $password, $db_name);

        // Create table
        $sql = "CREATE TABLE IF NOT EXISTS sensitive_data(
        id int AUTO_INCREMENT PRIMARY KEY,
        password varchar(80),
        iban varchar(34));";
        if ($conn->query($sql) === TRUE) {
            // echo "Table bank_transfers created successfully";
        } else {
            echo "Error creating table: " . $conn->error;
        }


        //Insert sensitive data table
        $sql = "INSERT INTO sensitive_data(password,iban) VALUES ('b833c13423d93e310684652ccd51750f7e74cdce','32ec0f1f7fd5568a59ffe6794575eb0d00'),('6625e2cddbf136890206d6e7769f3453d24d6c8b','e9ffbc652336a2545c59d2cb5d1295f566'),('adf5b09c4052bea447f3aed6d88af33a3a2f480a','c8b6ab06e9d859808f3807c1c4016692cd'),('3e012fb3d3050dd74c7683f977680ed02e96e10a','dec15a7e222c1867b05c1fe20548816fb0'),('f495aa319af382cb90a69e9662b276e1ae80ce79','88a0b3d6315f7510b2e5960bd158a31a9f'),('e2498d929e845943eb80e7cbcea059a79e05cddf','6060b8972e3bfdbc8b4f8ea4b2f1ed3deb'),('b73f05b72efa358ba42d00762e03952f3dac55dd','50062a701cde68ff75365f8ce8b329443a'),('be763955ceeebf91f26ced1a49e849b91257d34c','3522fd9a7b15040f13efab84ca8c442263'),('b4b4c668737a714277ff02f09968d75547c0f40c','9c890668bebfce8a7b01e32206580d1d19'),('39948a9f43b169260a2bce412e2acdb1916f7b91','9ed0afb57b9dd0c0bfacc1b7cc78c0f389'),('072354cb816ba067d3f3e03307f3930e121b3a5f','2fd3c0ca08edc7564a7c2413d923b1aeef'),('153d2a2c8d4821a1176291b968c82ade8a3e1123','848bbef700368a6b1ef0332fedba51138d'),('fa2e8b25ebd43455ac09de8a260ec0207f89ca84','00e6d12e4f0d7c8ec6ddae0c4a9abeca17'),('574bb91b90e5ab2c7bc37d261d2069f8cc0149e7','418d78817bc06335d45ae691f25d182573'),('cbc5bc926f4b4316e28d1fca8f31d93a598c8437','9d7b730f1eddf9626ecd35b7911d89a38c'),('108c3658020dd3a655c0dcca603e0655eaaaca49','4250e03679226ab382512461e1b024b508'),('dee41f15a5e601ccdd5e8985e8588496700e3181','c0be532249f769de9ee706bdc4baa1faad'),('74b5f9e6b4cdc58c7009df214091199c2c627ff1','25e0780202ef4aa9cedf62f382d33fdd07'),('8fa1d05bd48cf52c4d250593ac46005573ef1006','adc2744abcc59d1a65d085b4d2d8009410'),('821d14341ec88d87611cb3adfa93f8645c3441e8','522ac9df3cdf6993c1c5f6f43d6bd065c6'),('d3054c50650b561b2cc251b2c758af859802d870','b276a5a0c2c2fc23afedfc85ca6f0ed18e'),('d5f747804df953aa23462639de5602f69af2daf2','e9c3d4e19713616ba614fdf50934ad6927'),('43b032c5055a7b41eb3ca1f3c962404ea23a386d','06009c5beab2340172593540bba833c018'),('ce8f7f87c715d33fc866cd72727847e694564323','00407ec78970b265d875e07aff44a454e7'),('af717e2b0bec61009b20bb2042e13adcae794c91','600f24403161e04103409893d3ec24bfc5'),('2caa68b3b017d964293998821018a7af6278cd02','b695dde31d2f470a8c76d68ed689e395cb'),('bc5d2f6d7293310b0f587a31c21cc33d97257a39','e2350a8e9e56d512ac1dd69aaf73b32e5f'),('fcb85d733c78d6d31d2eaafdbe15006d926005ed','73328205d7dc84526519147e5cabd371d5'),('72bd55f8a3ebb64a35e79474fd69fda9f8536740','5833935c9f175e313793dbc3bcb4a340be'),('107ee1d6f50ee70c020a6b0bf67052cdeff67b0b','8559e223ee06facb9097f0ffd8b41ae675'),('237f97add0f38a20bed8b47155375cede7a53520','e50bf18d2ef25fca9fff5137ef93e77b26'),('8a030add35441971949c55f59f02ec442750288d','579736571bc5eb7e0df79a65c8caf42e90'),('6966919e2da55964d7b4b383b0591305a16e8f82','c2d3910e959ef45a43465b23ac3d1b70e8'),('5d2f20930077146163e9385fe0c6067c7d322cf8','80ef2f57a025794578f64b17089dbc26d6'),('351592fe4e660ea83e5806109cee6d7407e5dbc8','a0b9e04754daa2fb31afc875132e392193'),('55a2003a2f43878047dd8fc65c861ff657d17342','0f14d5afbb7ea2192a7fd21923a2a34c34'),('25f220f6304134cf83f6aa28887b00ccd1ab0f9a','40ca89bd1050a316acc2313995e7e6e31a'),('9c52b7cfea02ddd82005052569b046f90d7104ae','8754bc04a2880fb2be567d4ac37f229213'),('e29d6849ae80f43d83c33a8739645b499fada0e4','768a4a66e127495d3a9c551064deae36c1'),('70be615d91e315daef944a34903be6845075d069','29ec81d6fadc853f7a40e340b014482ec7'),('627b514e50ed2b8f6abfe84e52f3fbf7967c2c65','ea7a9256d83c42da331b51595349b03dbd'),('2988cbfdba66896bf920558bb031e31442d03074','96c5c3dcc051c3676899f57f5dd6744167'),('58d3602f3714d08c5499906d0f1ee9d6f7fea620','bb0f80010c45b45b1d6f3bc712512abd2b'),('a2bcff89d1e3cae2bb6f0b489738937ac8e5ef4f','3a70c16b5b1150690f6783a1036873d91d'),('e61d4c6cc2aabcab1eaf33da9e4149dfbec9e114','4e52aa1a38038ba5ce07997d81e4cb0b1f'),('0e008dda85511e21faf430162ca2dd16d11fd0fb','c311717eba07c9560971970b34d628eb50'),('8e75f8697a635005e49969671255de92ebdc837e','4ce5afd607bff0fd6a5372b35ff98b2310'),('238f58b69d0e7f8122b5abd7bdf3009ad5804a35','8cddcd1508e1bdc89d9d7660194ea0aabb'),('908546d5a528992bb20d8fed0ebf75de8cbe5a67','4b74e3f3e4bc036ae06004ab3db2e52fe3'),('afb15819929966854d51fdfed1228d25e7770ff9','7bd9bf979b5d1b82e341d7f9da3dc25a5b');";
        if ($conn->query($sql) === TRUE) {
            // echo "Data insert correctly";
        } else {
            echo "Error creating table: " . $conn->error;
        }


        function db_connection($server, $username, $password, $db_name)
        {
            // Create connection
            $conn = new mysqli($server, $username, $password, $db_name);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            return $conn;
        }

                $sql = "SELECT * FROM sensitive_data;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                ?>

                        <tr>
                            <th scope="row"><?= $row['id'] ?></th>
                            <th><?= $row['password'] ?></th>
                            <th><?= $row['iban'] ?></th>
                        </tr>
                        <br>
                <?php
                    }
                } else {
                    echo "0 results";
                }

                ?>

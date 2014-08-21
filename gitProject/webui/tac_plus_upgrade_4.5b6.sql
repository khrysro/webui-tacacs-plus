-- This file created for WebUI and tac_plus from www.networkforums.net
-- Author:      Andrew Young
-- Last Update: 08/30/2009
-- Release:	4.5b6

use tacacs;
ALTER TABLE user ADD flags int default 2;
UPDATE user set flags=0;

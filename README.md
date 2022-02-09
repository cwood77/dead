# Dead

A simple 'bucket list' website used to learn web tech.

# First Time Setup (once only)
- [host] Run `init.bat`  (This just creates some empty folders so vagrant isn't unhappy later.)
- [host] Run `vagrant up`
- [host] Run `vagrant ssh`
- [vm] Run `~/priv/set-sql-password.sh [oldpasswd] [newpasswd]`, probably also `sudo passwd`.  This will generate files (not checked in) PHP uses to access the database.

# Credits
The vagrant file and provisioning script are based on davidecesarano/vagrant-lamp
Thnks!

# Dead

# First Time Setup (once only)
- [host] Run `init.bat`  (This just creates some empty folders so vagrant isn't unhappy later.)
- [host] Run `vagrant up`
- [host] Run `vagrant ssh`
- [vm] Run `~/priv/set-sql-password.sh [oldpasswd] [newpasswd]`, probably also `sudo passwd`.  This will generate files (not checked in) PHP uses to access the database.

# Prod/Dev Setup [under construction]
By now, you have a single VM that you can develop on directly.  If you want a seperate dev instance, follow these steps.

## Preface
- You'll need two instance of the repo cloned; one for prod and one for dev.

## On Prod
- [host] Run `init.bat prod`
- [host] Run `publish.bat` to resync master, or setup a scheduled task

## On Dev
- [host] Run `init.bat dev`
- [host] Run `fork-db.bat` to copy the DB from master.  (Make sure the VM is not running)

# Credits
The vagrant file and provisioning script are based on davidecesarano/vagrant-lamp
Thnks!

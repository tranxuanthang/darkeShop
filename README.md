# darkeShop
Source code for making websites that help people find and install cia/eShop titles easier.
##pem and key files
Find ctr-common-1.crt and ctr-common-1.key files in https://github.com/Plailect/PlaiCDN and convert the crt file to pem using openssl:
```
openssl x509 -in ctr-common-1.crt -out ctr-common-1.pem -outform PEM
```

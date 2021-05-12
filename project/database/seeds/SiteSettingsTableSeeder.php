<?php

use App\Models\SiteSettings;
use Illuminate\Database\Seeder;
use App\Services\SettingsManager;

class SiteSettingsTableSeeder extends Seeder
{
    protected $settingsManager;
    /**
     * Constructor Method
     *
     * @return void
     */
    public function __construct()
    {
        $this->settingsManager = new SettingsManager();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //GLOBAL SETTINGS
        $this->set('SITE_NAME', 'Site Name', 'Site name will be used in emails, messages etc.', 'text');
        $this->set('SITE_URL', 'Site URL', 'Site url', 'text');
        $this->set('SITE_COPYRIGHT', 'Site Copyright', 'Site copyright owner (displayed in footer section).', 'text');
        $this->set('SITE_LOGO', 'Site Logo', 'Site logo', 'image');
        $this->set('SITE_LOGO_INVERT', 'Invert Site Logo Colors', 'Set this to 1 if your logo has transparent bg and light text colored. In this case its colors needs to get inverted when the header is scrolled down and becomes white background. Otherwise will not be visible.', 'text');
        $this->set('SITE_FAVICON', 'Site Favicon', 'Site favicon', 'file');
        $this->set('SITE_CONTACT_EMAIL', 'Site Contact Email', 'Site contact email', 'text');

        $this->set('SOCIAL_FACEBOOK_URL', 'Facebook Url', 'Facebook Url (Please enter full url including http:// prefix)', 'text', 'https://exbita.com');
        $this->set('SOCIAL_TWITTER_URL', 'Twitter Url', 'Twitter Url (Please enter full url including http:// prefix )', 'text', 'https://exbita.com');

        //EMAIL SETTINGS
        $this->set('MAIL_DRIVER', 'Mail Driver', 'Mail Driver', 'text','', false, true);
        $this->set('MAIL_HOST', 'Mail Host', 'Mail service smpt url.', 'text', '');
        $this->set('MAIL_PORT', 'Mail Port', 'Mail service smpt port', 'text', '');
        $this->set('MAIL_USERNAME', 'Mail Username', 'Mail service username or email', 'text', '');
        $this->set('MAIL_PASSWORD', 'Mail Password', 'Mail service password', 'text', '', true);
        $this->set('MAIL_FROM_ADDRESS', 'Mail Sender Address', 'Mail service sender email address', 'text', '');
        $this->set('MAIL_FROM_NAME', 'Mail Sender Name', 'Mail service sender name', 'text','');
        $this->set('MAIL_ENCRYPTION', 'Mail Encryption', 'Mail Encryption', 'text','');
        $this->set('MAIL_TEST_ADDRESS', 'Mail Test Address', 'Test Email will be sent to this address on Health Checker Section', 'text','');

        //AWS S3 SETTINGS
        $this->set('S3_KEY', 'AWS S3 Key', 'Your AWS Key that has access to your bucket', 'text', '', true);
        $this->set('S3_SECRET', 'AWS S3 Secret', 'Your AWS Secret that has access to your bucket', 'text', '', true);
        $this->set('S3_REGION', 'AWS S3 Region', 'AWS region in which your bucket is created (eu-central-1, eu-west-2 etc.)', 'text', '');
        $this->set('S3_BUCKET', 'AWS S3 Bucket Name', 'AWS S3 bucket name which will store user and site assets', 'text', '');
        $this->set('S3_BUCKET_BACKUP', 'AWS S3 Backup Bucket Name', 'AWS S3 bucket name which will store your regular db backups', 'text', '');

        //GOOGLE NOCAPTCHA SETTINGS
        $this->set('NOCAPTCHA_SITEKEY', 'Captcha Site Key', 'Google NoCaptcha Site Key', 'text', '', true);
        $this->set('NOCAPTCHA_SECRET', 'Captcha Secret', 'Google NoCaptcha Secret', 'text', '', true);

        //SMS PROVIDER SETTINGS
        $this->set('CLICKATELL_TOKEN', 'Clickatell API Key', 'Clickatell API Key', 'text', '', true);
        $this->set('CLICKATELL_TEST_NUMBER', 'Clickatell Test Number', 'Clickatell Test Number', 'text', '', true);
        $this->set('SMS_SENDER_ID', 'Clickatell Sender Name', 'Clickatell Sender Name (by default Exbita)', 'text', '', false);

        //BITCOIND SETTINGS
        $this->set('BITCOIND_USER', 'Bitcoind User', 'Bitcoin Wallet RPC Username', 'text', '', true);
        $this->set('BITCOIND_PASSWORD', 'Bitcoind Password', 'Bitcoin Wallet RPC Password', 'text', '', true);
        $this->set('BITCOIND_HOST', 'Bitcoind Host', 'Bitcoin Wallet RPC Ip or Hostname', 'text', '');
        $this->set('BITCOIND_PORT', 'Bitcoind Port', 'Bitcoin Wallet RPC Port', 'text', '');
        $this->set('BITCOIND_CONFIRMATIONS_REQUIRED', 'Bitcoin Confirmations Required', 'Number of network confirmations before bitcoin deposits are added to user wallets', 'text', '');
        $this->set('BITCOIND_IS_TESTNET', 'Is Testnet?', 'Check if Bitcoind Server uses Testnet', 'checkbox', '');

        //ETHEREUM SETTINGS
        $this->set('ETHEREUM_USER', 'Ethereum User', 'Ethereum Wallet RPC Username', 'text', '', true);
        $this->set('ETHEREUM_PASSWORD', 'Ethereum Password', 'Ethereum Wallet RPC Password', 'text', '', true);
        $this->set('ETHEREUM_HOST', 'Ethereum Host', 'Ethereum Wallet RPC Ip or Hostname', 'text', '');
        $this->set('ETHEREUM_PORT', 'Ethereum Port', 'Ethereum Wallet RPC Port', 'text', '');
        $this->set('ETHEREUM_CONFIRMATIONS_REQUIRED', 'Ethereum Confirmations Required', 'Number of network confirmations before ethereum deposits are added to user wallets', 'text', '');
        $this->set('ETHEREUM_TRANSACTION_URL_LOOKUP', 'Ethereum Blockchain Explorer', 'Ethereum Blockchain Explorer for Transaction Lookup', 'text', '');

        //COINPAYMENTS SETTINGS
        $this->set('COINPAYMENTS_MERCHANT_ID', 'Merchant ID', 'Merchant ID', 'text', '', true);
        $this->set('COINPAYMENTS_PRIVATE_KEY', 'Private Key', 'Private Key', 'text', '', true);
        $this->set('COINPAYMENTS_PUBLIC_KEY', 'Public Key', 'Public Key', 'text', '', true);
        $this->set('COINPAYMENTS_IPN', 'IPN Secret', 'IPN Secret', 'text', '', true);
        $this->set('COINPAYMENTS_DEPOSIT_FEE', 'Check if exchange will pay Coinpayments deposit fee (0.5%)?', 'Check if exchange will pay Coinpayments deposit fee (0.5%)?', 'checkbox', '');

        // KYC STATE
        $this->set('KYC_STATE', 'Is KYC required?', 'Check if users has active means of identification to carry out sensitive actions', 'checkbox', '');

        // STORAGE STATE
        $this->set('STORAGE_STATE', 'Enable storing images in AWS Storage', 'Set up AWS Credentials before selecting this option', 'checkbox', '');

        // BROWSER DETECTION STATE
        $this->set('DISABLE_BROWSER_LANGUAGE_DETECTION', 'Disable browser language detection', 'Enable this option if you do not want to display site based on user own language', 'checkbox', '');
    }

    public function set($name, $label, $description, $type, $value = '', $sensitive = false, $skipSeeder = false)
    {
        $s = SiteSettings::where('name', $name)->first();

        // Define site settings env variables
        if($type !== 'image' && $type !== 'file' && !$skipSeeder && ($name != 'KYC_STATE') && config('settings.'.$name) == "") {
            $this->settingsManager->envUpdate($name, '');
        }

        if($s === null && ($name == 'KYC_STATE')) {
            $this->settingsManager->envUpdate($name, '1');
        }

        if ($s === null) {
            SiteSettings::create([
                'name' => $name,
                'label' => $label,
                'description' => $description,
                'type' => $type,
                'value' => $value,
                'is_sensitive' => $sensitive
            ]);
        } else {
            $s->description = $description;
            $s->label = $label;
            $s->type = $type;
            $s->is_sensitive = $sensitive;
            $s->update();
        }
    }
}

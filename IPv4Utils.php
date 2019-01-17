namespace utils\ipv4;

/**
 * Description of ipv4Utils
 *
 */
class IPv4Utils {
    
    /**
     * @var unsigned integer of the IP address
     */
    protected $IPAddress;
    
    /**
     * @var unsigned interger netmask
     */
    protected $Netmask;
    
    /**
     * @var unsigned interger wildcard netmask
     */
    protected $WcNetmask;
    
    /**
     * Constructor
     */
    public function __construct(string $ip='', string $netmask='255.255.255.0')
    {
        if ($ip != '')
        {
            if ($this->setIPfromString ($ip) === false)
            {
                throw new \Exception(__METHOD__.' requires valid IPv4 address string (aaa.bbb.ccc.ddd).');
            }
        }
        if ($netmask != '')
        {
            if ($this->setNetmaskfromString ($netmask) === false)
            {
                throw new \Exception(__METHOD__.' requires valid $netmask string (aaa.bbb.ccc.ddd).');
            }
        }
    }
    
    public function getIPIntAddress () : int
    {
        return $this->IPAddress;
    }
    
    public function getIPStringAddress () : string
    {
        return long2ip($this->IPAddress);
    }
            
    public function getNetworkAddress () : string
    {
        return getIPStringAddress($this->IPAddress & $this->Netmask);
    }
    
    public function getBroadcastAddress () : string
    {
        return getIPStringAddress($this->IPAddress | $this->WcNetmask);
    }
    
    public function setIPfromString (string $IPString) : bool
    {
        $tempIP = ip2long($IPString);
        if ($tempIP !== false)
        {
            //$this->IPAddress = sprintf('%u', $tempIP);
            $this->IPAddress = $tempIP;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function setNetmaskfromString (string $netmask) : bool
    {
        $tempNetmask = ip2long($netmask);
        if ($tempNetmask !== false)
        {
            //$this->Netmask = sprintf('%u', $tempNetmask);
            $this->Netmask = $tempNetmask;
            $this->WcNetmask=long2ip( ~ip2long($tempNetmask) );
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function subtrack ($value)
    {
        $this->IPAddress = $this->IPAddress - $value;
    }
    
    public function add ($value)
    {
        $this->IPAddress = $this->IPAddress + $value;
    }
    
    public function mask2cidr() : int
    {  
        $base = ip2long('255.255.255.255');
        return 32-log(($this->Netmask ^ $base)+1,2);       
    } 
}

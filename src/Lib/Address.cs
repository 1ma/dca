namespace DCA.Lib;

public readonly struct Address
{
    private readonly string addy;

    public Address(string address)
    {
        this.addy = address;
    }

    public override string ToString()
    {
        return this.addy;
    }
}

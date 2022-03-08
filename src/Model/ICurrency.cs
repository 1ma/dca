namespace DCA.Model;

public interface ICurrency
{
    public string GetSymbol();
    public uint GetExponent();
    public ulong GetRawRepresentation();
}

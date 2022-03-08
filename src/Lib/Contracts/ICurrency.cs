namespace DCA.Lib.Contracts;

public interface ICurrency
{
    public string GetSymbol();
    public uint GetExponent();
    public ulong GetRawRepresentation();
}

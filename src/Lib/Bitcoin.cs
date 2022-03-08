using DCA.Lib.Contracts;

namespace DCA.Lib;

public struct Bitcoin : ICurrency
{
    public string GetSymbol()
    {
        return "BTC";
    }

    public uint GetExponent()
    {
        return 8;
    }

    public ulong GetRawRepresentation()
    {
        return 0;
    }

    public override string ToString()
    {
        return "";
    }
}

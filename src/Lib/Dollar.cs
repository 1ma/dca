using DCA.Lib.Contracts;

namespace DCA.Lib;

public readonly struct Dollar : ICurrency
{
    public string GetSymbol()
    {
        return "USD";
    }

    public uint GetExponent()
    {
        return 2;
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

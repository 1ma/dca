using DCA.Lib.Contracts;

namespace DCA.Lib;

public readonly struct Euro : ICurrency
{
    public string GetSymbol()
    {
        return "EUR";
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

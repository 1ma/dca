namespace DCA.Lib.Contracts;

public interface IDollarConverter
{
    public Bitcoin ToBitcoin(Dollar amount);
    public Euro ToEuro(Dollar amount);
}

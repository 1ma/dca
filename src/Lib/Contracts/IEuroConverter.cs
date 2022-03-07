namespace DCA.Lib.Contracts;

public interface IEuroConverter
{
    public Bitcoin ToBitcoin(Euro amount);
    public Dollar ToDollar(Euro amount);
}
